<?php
// views/layout/navbar.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <!-- شعار الموقع ورابط الصفحة الرئيسية -->
        <a class="navbar-brand fw-bold" href="/alzikrayat/public/photos">الذكريات</a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="تبديل القائمة">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="/alzikrayat/public/photos">المعرض</a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-3">
                <?php if (isset($_SESSION['user'])): ?>
                    <!-- للمستخدم المسجل: عرض الاسم الأول مع أزرار التحكم -->
                    <span class="text-white fw-semibold">
                        Hi <?= htmlspecialchars($_SESSION['user']['first_name'] ?? 'User') ?>
                    </span>
                    <a href="/alzikrayat/public/upload" class="btn btn-sm btn-outline-light">رفع صورة</a>
                    <a href="/alzikrayat/public/logout" class="btn btn-sm btn-danger">Logout</a>
                <?php else: ?>
                    <!-- للزائر غير المسجل -->
                    <span class="text-white-50">Please Login</span>
                    <a href="/alzikrayat/public/login" class="btn btn-sm btn-light">Login</a>
                    <a href="/alzikrayat/public/register" class="btn btn-sm btn-outline-light">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>