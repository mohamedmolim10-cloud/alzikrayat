<?php
class Photo {
    public function __construct(private PDO $pdo) {}

    // حفظ بيانات الصورة
    public function create($userId, $fileName, $title, $description) {
        return $this->pdo->prepare("INSERT INTO photos (user_id, file_name, title, description) VALUES (?, ?, ?, ?)")
                         ->execute([$userId, $fileName, $title, $description]);
    }

    // جلب الكل أو صورة محددة (دالة واحدة تؤدي المهمتين)
    public function get($id = null) {
        $sql = "SELECT p.*, u.first_name, u.last_name FROM photos p JOIN users u ON p.user_id = u.id";
        if ($id) {
            $stmt = $this->pdo->prepare("$sql WHERE p.id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return $this->pdo->query("$sql ORDER BY p.date_time DESC")->fetchAll(PDO::FETCH_ASSOC);
    }
}