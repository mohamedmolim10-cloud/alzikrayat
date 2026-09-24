<?php
// controllers/PhotoController.php
require_once '../models/Photo.php';
require_once '../models/Comment.php';

class PhotoController {
    private $pdo;
    private $photoModel;
    private $commentModel;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->photoModel = new Photo($pdo);
        $this->commentModel = new Comment($pdo);
    }

    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $photos = $this->photoModel->getAllPhotos();
        require_once '../views/photos/index.php';
    }

    public function create() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user'])) {
            header('Location: /alzikrayat/public/login');
            exit;
        }
        $error = '';
        require_once '../views/photos/create.php';
    }

    public function store() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user'])) {
            header('Location: /alzikrayat/public/login');
            exit;
        }

        $error = '';
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $userId = $_SESSION['user']['id'];

        if (empty($title)) {
            $error = 'يرجى إدخال عنوان للصورة!';
        } elseif (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $error = 'يرجى اختيار ملف صورة صالح!';
        } else {
            $uploadedFile = $_FILES['image'];
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
            $fileExtension = strtolower(pathinfo($uploadedFile['name'], PATHINFO_EXTENSION));

            if (!in_array($fileExtension, $allowedExtensions)) {
                $error = 'صيغة الملف غير مدعومة!';
            } elseif ($uploadedFile['size'] > 5 * 1024 * 1024) {
                $error = 'حجم الصورة يتجاوز الحد المسموح (5MB)!';
            } else {
                $uniqueFileName = 'photo_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $fileExtension;
                $targetDirectory = __DIR__ . '/../public/images/uploads/';

                if (!is_dir($targetDirectory)) {
                    mkdir($targetDirectory, 0777, true);
                }

                $destinationPath = $targetDirectory . $uniqueFileName;

                if (move_uploaded_file($uploadedFile['tmp_name'], $destinationPath)) {
                    if ($this->photoModel->create($userId, $uniqueFileName, $title, $description)) {
                        header('Location: /alzikrayat/public/photos');
                        exit;
                    } else {
                        if (file_exists($destinationPath)) unlink($destinationPath);
                        $error = 'حدث خطأ أثناء حفظ الصورة في قاعدة البيانات!';
                    }
                } else {
                    $error = 'فشل نقل الصورة لمجلد التخزين!';
                }
            }
        }
        require_once '../views/photos/create.php';
    }

    /**
     * عرض تفاصيل الصورة والتعليقات
     */
    public function show($id) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $photo = $this->photoModel->findById($id);
        if (!$photo) {
            header('Location: /alzikrayat/public/photos');
            exit;
        }

        $comments = $this->commentModel->getByPhotoId($id);
        require_once '../views/photos/show.php';
    }

    /**
     * حفظ تعليق جديد على الصورة
     */
    public function addComment($photoId) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header('Location: /alzikrayat/public/login');
            exit;
        }

        $commentText = trim($_POST['comment'] ?? '');
        if (!empty($commentText)) {
            $this->commentModel->addComment($photoId, $_SESSION['user']['id'], $commentText);
        }

        header("Location: /alzikrayat/public/photo/{$photoId}");
        exit;
    }

    /**
     * حذف الصورة مع فحص ملكية المستخدم الصارم
     */
    public function delete($id) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header('Location: /alzikrayat/public/login');
            exit;
        }

        $photo = $this->photoModel->findById($id);

        // التحقق من وجود الصورة ومن أنها تخص المستخدم الحالي حصراً
        if ($photo && (int)$photo['user_id'] === (int)$_SESSION['user']['id']) {
            // حذف الملف الفعلي من القرص
            $filePath = __DIR__ . '/../public/images/uploads/' . $photo['file_name'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            // حذف السجل من قاعدة البيانات (ستُحذف التعليقات تلقائياً عبر Cascade Delete)
            $this->photoModel->delete($id);
        }

        header('Location: /alzikrayat/public/photos');
        exit;
    }
}