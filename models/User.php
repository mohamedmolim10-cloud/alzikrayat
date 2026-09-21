<?php
class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // التحقق من وجود البريد الإلكتروني مسبقاً
    public function findByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    // إدخال مستخدم جديد وتشفير كلمة المرور
    public function register($firstName, $lastName, $email, $password, $location, $occupation, $description) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (first_name, last_name, email, password, location, occupation, description) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$firstName, $lastName, $email, $hashedPassword, $location, $occupation, $description]);
    }
}