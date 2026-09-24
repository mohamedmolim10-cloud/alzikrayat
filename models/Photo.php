<?php
// models/Photo.php

class Photo {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * حفظ بيانات الصورة المرفوعة
     */
    public function create($userId, $fileName, $title, $description) {
        $sql = "INSERT INTO photos (user_id, file_name, title, description, date_time) 
                VALUES (:user_id, :file_name, :title, :description, NOW())";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':user_id'     => $userId,
            ':file_name'   => $fileName,
            ':title'       => $title,
            ':description' => $description
        ]);
    }

    /**
     * جلب جميع الصور للمعرض الرئيسي
     */
    public function getAllPhotos() {
        $sql = "SELECT p.*, u.first_name, u.last_name 
                FROM photos p 
                JOIN users u ON p.user_id = u.id 
                ORDER BY p.date_time DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * جلب تفاصيل صورة واحدة مع بيانات ناشرها
     */
    public function findById($id) {
        $sql = "SELECT p.*, u.first_name, u.last_name 
                FROM photos p 
                JOIN users u ON p.user_id = u.id 
                WHERE p.id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * حذف الصورة من قاعدة البيانات
     */
    public function delete($id) {
        $sql = "DELETE FROM photos WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}