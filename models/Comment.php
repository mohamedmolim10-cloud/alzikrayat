<?php
// models/Comment.php

class Comment {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * جلب كافة التعليقات الخاصة بصورة معينة مرتبة زمنياً
     */
    public function getByPhotoId($photoId) {
        $sql = "SELECT c.*, u.first_name, u.last_name 
                FROM comments c 
                JOIN users u ON c.user_id = u.id 
                WHERE c.photo_id = :photo_id 
                ORDER BY c.date_time ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':photo_id' => $photoId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * إضافة تعليق جديد على صورة
     */
    public function addComment($photoId, $userId, $commentText) {
        $sql = "INSERT INTO comments (photo_id, user_id, comment, date_time) 
                VALUES (:photo_id, :user_id, :comment, NOW())";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':photo_id' => $photoId,
            ':user_id'  => $userId,
            ':comment'  => $commentText
        ]);
    }
}