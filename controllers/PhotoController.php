<?php
require_once __DIR__ . '/../models/Photo.php';

class PhotoController {
    private Photo $photoModel;

    public function __construct($pdo) {
        $this->photoModel = new Photo($pdo);
    }

    // معالجة رفع الصورة
    public function upload() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
            $title = trim($_POST['title'] ?? '');
            $desc  = trim($_POST['description'] ?? '');
            $file  = $_FILES['image'];

            // التأكد من رفع ملف بصيغة صورة وتوليد اسم فريد
            $allowed = ['image/jpeg', 'image/png', 'image/webp'];
            if (in_array($file['type'], $allowed) && $file['error'] === 0) {
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $newName = uniqid('img_', true) . '.' . $ext;
                $target = __DIR__ . '/../public/images/uploads/' . $newName;

                if (move_uploaded_file($file['tmp_name'], $target)) {
                    $this->photoModel->create($_SESSION['user_id'], $newName, $title, $desc);
                    header('Location: /gallery');
                    exit;
                }
            }
        }
    }
}