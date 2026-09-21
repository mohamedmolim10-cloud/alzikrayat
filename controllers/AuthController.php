<?php
require_once '../models/User.php';

class AuthController {
    private $pdo;
    private $userModel;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->userModel = new User($pdo);
    }

    // عرض واجهة التسجيل (GET)
    public function showRegister() {
        $error = '';
        $success = '';
        require_once '../views/auth/register.php';
    }

    // معالجة بيانات التسجيل (POST)
    public function handleRegister() {
        $error = '';
        $success = '';

        // استلام البيانات وتطهيرها
        $firstName   = trim($_POST['first_name'] ?? '');
        $lastName    = trim($_POST['last_name'] ?? '');
        $email       = trim($_POST['email'] ?? '');
        $password    = $_POST['password'] ?? '';
        $location    = trim($_POST['location'] ?? '');
        $occupation  = trim($_POST['occupation'] ?? '');
        $description = trim($_POST['description'] ?? '');

        // التحقق من صحة المدخلات
        if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
            $error = "يرجى ملء جميع الحقول الإجبارية!";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "صيغة البريد الإلكتروني غير صحيحة!";
        } elseif ($this->userModel->findByEmail($email)) {
            $error = "هذا البريد الإلكتروني مسجل مسبقاً!";
        } else {
            // استدعاء الموديل للحفظ والتشفير
            if ($this->userModel->register($firstName, $lastName, $email, $password, $location, $occupation, $description)) {
                $success = "تم إنشاء الحساب بنجاح! يمكنك الآن <a href='/alzikrayat/public/login'>تسجيل الدخول</a>.";
            } else {
                $error = "حدث خطأ أثناء حفظ البيانات!";
            }
        }

        require_once '../views/auth/register.php';
    }

    // عرض واجهة تسجيل الدخول (GET)
    public function showLogin() {
        $error = '';
        require_once '../views/auth/login.php';
    }

    // معالجة تسجيل الدخول (POST)
    public function handleLogin() {
        $error = '';
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $error = "يرجى إدخال البريد الإلكتروني وكلمة المرور!";
        } else {
            $user = $this->userModel->findByEmail($email);

            // التحقق من تطابق كلمة المرور المشفرة
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                // حفظ تاريخ وتوقيت آخر تسجيل دخول في الكوكي لمدة 7 أيام
            setcookie('last_login', date('Y-m-d H:i:s'), time() + (7 * 24 * 60 * 60), "/");
                
                echo "
                <div style='font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; height: 80vh; background-color: #f8fafc; margin: 0;'>
                    <div style='background: white; padding: 40px 60px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); text-align: center; border-top: 5px solid #0d6efd;'>
                        <h1 style='color: #1e293b; margin-bottom: 10px; font-size: 28px;'>Welcome, " . htmlspecialchars($user['first_name']) . "! 👋</h1>
                        <p style='color: #64748b; font-size: 16px; margin-bottom: 20px;'>تم تسجيل دخولك إلى معرض الذكريات بنجاح.</p>
                        <a href='/alzikrayat/public/logout' style='display: inline-block; padding: 10px 20px; background-color: #dc3545; color: white; text-decoration: none; border-radius: 8px; font-weight: bold;'>تسجيل الخروج</a>
                    </div>
                </div>
                ";
                exit;
            } else {
                $error = "بيانات الدخول غير صحيحة!";
            }
        }

        require_once '../views/auth/login.php';
    }

    // تسجيل الخروج وإنهاء الجلسة (GET)
    public function logout() {
        session_unset();
        session_destroy();
        header('Location: /alzikrayat/public/login');
        exit;
    }
}