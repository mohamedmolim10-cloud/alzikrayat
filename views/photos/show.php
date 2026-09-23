<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($photo['title']) ?> - التفاصيل</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container my-5">
        <a href="/photos" class="btn btn-secondary btn-sm mb-3">← العودة للألبوم</a>

        <div class="card shadow-sm mx-auto" style="max-width: 700px;">
            <img src="/images/uploads/<?= htmlspecialchars($photo['file_name']) ?>" class="card-img-top" alt="<?= htmlspecialchars($photo['title']) ?>">
            
            <div class="card-body">
                <h3 class="card-title"><?= htmlspecialchars($photo['title']) ?></h3>
                <p class="text-muted small">
                    نُشر بواسطة: <?= htmlspecialchars($photo['first_name'] . ' ' . $photo['last_name']) ?> 
                    في <?= $photo['date_time'] ?>
                </p>
                <hr>
                <p class="card-text"><?= nl2br(htmlspecialchars($photo['description'])) ?></p>

                <!-- شرط الدليل: التحقق من الملكية لظهور زر الحذف فقط لصاحب الصورة -->
                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $photo['user_id']): ?>
                    <hr>
                    <a href="/photo/<?= $photo['id'] ?>/delete" 
                       class="btn btn-outline-danger btn-sm"
                       onclick="return confirm('هل أنت متأكد من رغبتك في حذف صورتك نهائياً؟');">
                       حذف الصورة
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

</body>
</html>