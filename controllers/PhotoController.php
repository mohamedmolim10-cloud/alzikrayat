<?php
require_once __DIR__ . '/../models/Photo.php';

class PhotoController {
    private Photo $photoModel;

    public function __construct($pdo) {
        $this->photoModel = new Photo($pdo);
    }

    // عرض صفحة المعرض العام لجميع الصور
    public function index() {
        $photos = $this->photoModel->get();
        require_once __DIR__ . '/../views/photos/index.php';
    }

    // عرض تفاصيل صورة محددة
    public function show($id) {
        $photo = $this->photoModel->get($id);
        if (!$photo) {
            header('Location: /photos');
            exit;
        }
        require_once __DIR__ . '/../views/photos/show.php';
    }

    // معالجة رفع صورة جديدة
    public function upload() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
            $title = trim($_POST['title'] ?? '');
            $desc  = trim($_POST['description'] ?? '');
            $file  = $_FILES['image'];

            // التحقق من نوع الصورة
            $allowed = ['image/jpeg', 'image/png', 'image/webp'];
            if (in_array($file['type'], $allowed) && $file['error'] === 0) {
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $newName = uniqid('img_', true) . '.' . $ext;
                $target = __DIR__ . '/../public/images/uploads/' . $newName;

                if (move_uploaded_file($file['tmp_name'], $target)) {
                    $this->photoModel->create($_SESSION['user_id'], $newName, $title, $desc);
                    header('Location: /photos');
                    exit;
                }
            }
        }
    }
}