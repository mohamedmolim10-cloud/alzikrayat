<?php
// views/photos/index.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الذكريات - معرض الصور</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .photo-card img {
            height: 230px;
            object-fit: cover;
            width: 100%;
            transition: transform 0.3s ease;
        }
        .photo-card:hover img {
            transform: scale(1.03);
        }
        .hero-section {
            background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
            color: #fff;
            padding: 40px 0;
            margin-bottom: 30px;
            border-radius: 0 0 20px 20px;
        }
    </style>
</head>
<body class="bg-light">

    <!-- شريط التنقل (Navbar) -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/alzikrayat/public/photos">الذكريات</a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="/alzikrayat/public/photos">المعرض</a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-2">
                    <?php if (isset($_SESSION['user'])): ?>
                        <span class="text-white me-2">
                            Hi <?= htmlspecialchars($_SESSION['user']['first_name'] ?? 'User') ?>
                        </span>
                        <a href="/alzikrayat/public/upload" class="btn btn-sm btn-outline-light">رفع صورة</a>
                        <a href="/alzikrayat/public/logout" class="btn btn-sm btn-danger">Logout</a>
                    <?php else: ?>
                        <span class="text-white-50 me-2">Please Login</span>
                        <a href="/alzikrayat/public/login" class="btn btn-sm btn-light">Login</a>
                        <a href="/alzikrayat/public/register" class="btn btn-sm btn-outline-light">Register</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- الواجهة الترحيبية وقسم About Us -->
    <header class="hero-section text-center shadow-sm">
        <div class="container">
            <h1 class="fw-bold mb-3">منصة الذكريات (Alzikrayat)</h1>
            <p class="lead text-light col-md-8 mx-auto">
                مساحتك الخاصة لمشاركة أجمل اللحظات والذكريات مع زملائك وأصدقائك، وتوثيق الصور والتعليق عليها بسهولة وأمان.
            </p>
            <div class="d-flex justify-content-center gap-4 mt-3 text-white-50">
                <div><strong><?= !empty($photos) ? count($photos) : 0 ?></strong> صور معروضة</div>
                <div>•</div>
                <div>نظام MVC مخصص</div>
            </div>
        </div>
    </header>

    <!-- قسم المعرض والتحكم في أنماط الشبكة -->
    <main class="container mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <h3 class="fw-bold m-0">ألبوم الصور</h3>
            
            <!-- أزرار تغيير نمط العرض (Custom Grid Display Styles) -->
            <div class="btn-group" role="group" aria-label="أنماط العرض">
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="changeGrid('col-lg-4 col-md-6')">3 أعمدة</button>
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="changeGrid('col-lg-3 col-md-4 col-sm-6')">4 أعمدة</button>
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="changeGrid('col-12')">قائمة عريضة</button>
            </div>
        </div>

        <!-- شبكة عرض الصور -->
        <div class="row g-4" id="galleryGrid">
            <?php if (!empty($photos)): ?>
                <?php foreach ($photos as $photo): ?>
                    <div class="grid-item col-lg-4 col-md-6">
                        <div class="card h-100 shadow-sm photo-card border-0 rounded-3 overflow-hidden">
                            <div class="overflow-hidden bg-dark text-center" style="max-height: 230px;">
                                <img src="/alzikrayat/public/images/uploads/<?= htmlspecialchars($photo['file_name']) ?>" 
                                     class="card-img-top" 
                                     alt="<?= htmlspecialchars($photo['title']) ?>"
                                     onerror="this.src='https://via.placeholder.com/400x250?text=No+Image';">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title fw-bold text-dark mb-2"><?= htmlspecialchars($photo['title']) ?></h5>
                                <p class="card-text text-muted small">
                                    <?= htmlspecialchars(mb_strimwidth($photo['description'] ?? '', 0, 80, '...')) ?>
                                </p>
                            </div>
                            <div class="card-footer bg-white border-top-0 d-flex justify-content-between align-items-center pb-3">
                                <small class="text-secondary"><i class="far fa-clock me-1"></i><?= htmlspecialchars(substr($photo['date_time'], 0, 10)) ?></small>
                                <a href="/alzikrayat/public/photo/<?= $photo['id'] ?>" class="btn btn-sm btn-outline-primary fw-semibold">
                                    عرض التفاصيل &larr;
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <div class="alert alert-info py-4">
                        <h5 class="m-0">لا توجد صور منشورة حتى الآن.</h5>
                        <?php if (isset($_SESSION['user'])): ?>
                            <p class="mt-2 mb-0">كن أول من يشارك صورة عبر الضغط على زر "رفع صورة" بالأعلى!</p>
                        <?php else: ?>
                            <p class="mt-2 mb-0">سجّل دخولك الآن لتتمكن من إضافة أول صورة.</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- JavaScript لتبديل شكل الشبكة ديناميكياً -->
    <script>
        function changeGrid(columnClass) {
            const items = document.querySelectorAll('.grid-item');
            items.forEach(item => {
                item.className = 'grid-item ' + columnClass;
            });
        }
    </script>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>