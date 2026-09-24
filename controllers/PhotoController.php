<?php
/**
 * متحكم إدارة الصور (PhotoController)
 * مسؤول عن تنسيق استعراض الصور في المعرض وإدارة رفع الصور الجديدة وتخزينها
 * وفق معمارية الـ MVC وبدون استخدام أي مكاتب أو أطر عمل خارجية
 */

require_once '../models/Photo.php';

class PhotoController {
    /**
     * اتصال قاعدة البيانات PDO
     * @var PDO
     */
    private $pdo;

    /**
     * كائن موديل الصور Photo
     * @var Photo
     */
    private $photoModel;

    /**
     * دالة البناء وتمرير اتصال قاعدة البيانات
     * @param PDO $pdo
     */
    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->photoModel = new Photo($pdo);
    }

    /**
     * عرض الصفحة الرئيسية لمعرض الصور
     * تجلب قائمة الصور من قاعدة البيانات وتمررها لواجهة العرض
     * @return void
     */
    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $photos = $this->photoModel->getAllPhotos();
        require_once '../views/photos/index.php';
    }

    /**
     * عرض نموذج رفع صورة جديدة
     * تمنع الوصول للزوار غير المسجلين وتعيد توجيههم لصفحة تسجيل الدخول
     * @return void
     */
    public function create() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // فحص صلاحية الدخول
        if (!isset($_SESSION['user'])) {
            header('Location: /alzikrayat/public/login');
            exit;
        }

        $error = '';
        require_once '../views/photos/create.php';
    }

    /**
     * معالجة تخزين الصورة ورفع الملف على السيرفر
     * تتضمن التحقق من الحقول ونوع وامتداد وحجم الملف أمنياً
     * @return void
     */
    public function store() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // فحص صلاحية الدخول
        if (!isset($_SESSION['user'])) {
            header('Location: /alzikrayat/public/login');
            exit;
        }

        $error = '';
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $userId = $_SESSION['user']['id'];

        // طبقة التحقق من جهة السيرفر (Server-side validation)
        if (empty($title)) {
            $error = 'يرجى إدخال عنوان للصورة!';
        } elseif (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $error = 'يرجى اختيار ملف صورة صالح أو التأكد من سلامة التحميل!';
        } else {
            $uploadedFile = $_FILES['image'];
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
            $fileExtension = strtolower(pathinfo($uploadedFile['name'], PATHINFO_EXTENSION));

            if (!in_array($fileExtension, $allowedExtensions)) {
                $error = 'صيغة الملف غير مدعومة! الصيغ المسموحة: JPG, JPEG, PNG, WEBP.';
            } elseif ($uploadedFile['size'] > 5 * 1024 * 1024) {
                $error = 'حجم الصورة كبير جداً! الحد الأقصى المسموح هو 5 ميجابايت.';
            } else {
                // توليد اسم ملف فريد وآمن لمنع التعارض وثغرات حقن الأسماء
                $uniqueFileName = 'photo_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $fileExtension;
                $targetDirectory = __DIR__ . '/../public/images/uploads/';

                // إنشاء مجلد الرفع في حال لم يكن موجوداً
                if (!is_dir($targetDirectory)) {
                    mkdir($targetDirectory, 0777, true);
                }

                $destinationPath = $targetDirectory . $uniqueFileName;

                // نقل الملف من المسار المؤقت إلى مجلد التخزين الفعلي
                if (move_uploaded_file($uploadedFile['tmp_name'], $destinationPath)) {
                    // حفظ البيانات الوصفية للصورة في قاعدة البيانات
                    $isSaved = $this->photoModel->create($userId, $uniqueFileName, $title, $description);

                    if ($isSaved) {
                        header('Location: /alzikrayat/public/photos');
                        exit;
                    } else {
                        // حذف الصورة من القرص إذا فشل الحفظ في قاعدة البيانات
                        if (file_exists($destinationPath)) {
                            unlink($destinationPath);
                        }
                        $error = 'حدث خطأ أثناء حفظ معلومات الصورة في قاعدة البيانات!';
                    }
                } else {
                    $error = 'فشل حفظ الملف على السيرفر! تأكد من صلاحيات المجلد.';
                }
            }
        }

        // في حال وجود أي خطأ، إعادة عرض النموذج مع رسالة التنبيه
        require_once '../views/photos/create.php';
    }
}