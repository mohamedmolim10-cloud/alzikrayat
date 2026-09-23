<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>معرض الذكريات</title>
    <!-- Bootstrap CSS الرسمي المطلوب في الدليل -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- شريط التنقل المتطابق مع شرط SUST: إظهار Hi <name> أو Please Login -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/photos">الذكريات</a>
            <div class="d-flex align-items-center">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span class="navbar-text me-3 text-white">
                        Hi <?= htmlspecialchars($_SESSION['first_name'] ?? 'User') ?>
                    </span>
                    <a href="/upload" class="btn btn-outline-light btn-sm me-2">رفع صورة</a>
                    <a href="/logout" class="btn btn-danger btn-sm">Logout</a>
                <?php else: ?>
                    <a href="/login" class="btn btn-outline-info btn-sm">Please Login</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- شبكة عرض الصور (Grid) -->
    <div class="container">
        <h3 class="mb-4">ألبوم الصور التشاركي</h3>
        
        <div class="row g-4">
            <?php if (!empty($photos)): ?>
                <?php foreach ($photos as $photo): ?>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm">
                            <img src="/images/uploads/<?= htmlspecialchars($photo['file_name']) ?>" 
                                 class="card-img-top" 
                                 style="height: 220px; object-fit: cover;" 
                                 alt="<?= htmlspecialchars($photo['title']) ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($photo['title']) ?></h5>
                                <p class="card-text text-muted small">
                                    بواسطة: <?= htmlspecialchars($photo['first_name'] . ' ' . $photo['last_name']) ?>
                                </p>
                                <a href="/photo/<?= $photo['id'] ?>" class="btn btn-primary btn-sm w-100">عرض التفاصيل</a>
                            </div>
                            <div class="card-footer text-muted small">
                                <?= date('Y-m-d', strtotime($photo['date_time'])) ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info">لا توجد صور بعد، كن أول من يشارك ذكرياته!</div>
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>