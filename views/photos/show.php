<?php
// views/photos/show.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isLoggedIn = isset($_SESSION['user']);
$currentUserId = $isLoggedIn ? $_SESSION['user']['id'] : null;
$isOwner = $isLoggedIn && ((int)$photo['user_id'] === (int)$currentUserId);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الذكريات - <?= htmlspecialchars($photo['title']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- شريط التنقل -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/alzikrayat/public/photos">الذكريات</a>
            <div class="d-flex align-items-center gap-3">
                <?php if ($isLoggedIn): ?>
                    <span class="text-white">Hi <?= htmlspecialchars($_SESSION['user']['first_name']) ?></span>
                    <a href="/alzikrayat/public/upload" class="btn btn-sm btn-primary">رفع صورة</a>
                    <a href="/alzikrayat/public/logout" class="btn btn-sm btn-danger">Logout</a>
                <?php else: ?>
                    <span class="text-white-50">Please Login</span>
                    <a href="/alzikrayat/public/login" class="btn btn-sm btn-outline-light">Login</a>
                    <a href="/alzikrayat/public/register" class="btn btn-sm btn-primary">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container my-4">
        <div class="mb-3">
            <a href="/alzikrayat/public/photos" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-right"></i> العودة للمعرض</a>
        </div>

        <div class="row g-4">
            <!-- تفاصيل الصورة والصورة عالية الدقة -->
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                    <img src="/alzikrayat/public/images/uploads/<?= htmlspecialchars($photo['file_name']) ?>" 
                         class="img-fluid w-100" style="max-height: 550px; object-fit: contain; background: #000;" 
                         alt="<?= htmlspecialchars($photo['title']) ?>">
                    
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h3 class="card-title fw-bold text-dark m-0"><?= htmlspecialchars($photo['title']) ?></h3>
                            
                            <!-- زر الحذف متاح للمالك فقط -->
                            <?php if ($isOwner): ?>
                                <a href="/alzikrayat/public/photo/<?= $photo['id'] ?>/delete" 
                                   class="btn btn-outline-danger btn-sm"
                                   onclick="return confirm('هل أنت متأكد من رغبتك في حذف هذه الصورة وجميع تعليقاتها نهائياً؟');">
                                    <i class="fas fa-trash-alt"></i> حذف الصورة
                                </a>
                            <?php endif; ?>
                        </div>

                        <p class="text-muted small mb-3">
                            بواسطة: <strong><?= htmlspecialchars($photo['first_name'] . ' ' . $photo['last_name']) ?></strong> 
                            | التاريخ: <?= htmlspecialchars($photo['date_time']) ?>
                        </p>

                        <?php if (!empty($photo['description'])): ?>
                            <p class="card-text text-secondary fs-6" style="white-space: pre-line;">
                                <?= htmlspecialchars($photo['description']) ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- قسم التعليقات -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="m-0 fw-bold"><i class="fas fa-comments text-primary me-2"></i> التعليقات (<?= count($comments) ?>)</h5>
                    </div>
                    
                    <div class="card-body p-3" style="max-height: 400px; overflow-y: auto;">
                        <?php if (empty($comments)): ?>
                            <p class="text-muted text-center my-4 small">لا توجد تعليقات حتى الآن. كن أول المعلقين!</p>
                        <?php else: ?>
                            <div class="d-flex flex-column gap-3">
                                <?php foreach ($comments as $c): ?>
                                    <div class="p-2 rounded bg-light border-start border-3 border-primary">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="fw-bold small text-dark"><?= htmlspecialchars($c['first_name'] . ' ' . $c['last_name']) ?></span>
                                            <span class="text-muted" style="font-size: 0.75rem;"><?= htmlspecialchars(substr($c['date_time'], 0, 16)) ?></span>
                                        </div>
                                        <p class="m-0 small text-secondary"><?= htmlspecialchars($c['comment']) ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- صندوق كتابة تعليق -->
                    <div class="card-footer bg-white p-3 border-top">
                        <?php if ($isLoggedIn): ?>
                            <form id="commentForm" action="/alzikrayat/public/photo/<?= $photo['id'] ?>/comment" method="POST">
                                <div class="mb-2">
                                    <textarea class="form-control form-control-sm" id="commentText" name="comment" rows="2" placeholder="اكتب تعليقك هنا..." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm w-100 fw-semibold">إرسال التعليق</button>
                            </form>
                        <?php else: ?>
                            <div class="alert alert-warning m-0 p-2 text-center small">
                                يجب عليك <a href="/alzikrayat/public/login" class="alert-link fw-bold">تسجيل الدخول</a> لتتمكن من كتابة تعليق[span_11](start_span)[span_11](end_span).
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- فحص التعليق من جهة العميل Client-side validation -->
    <script>
        const commentForm = document.getElementById('commentForm');
        if (commentForm) {
            commentForm.addEventListener('submit', function (e) {
                const commentText = document.getElementById('commentText').value.trim();
                if (commentText === '') {
                    alert('لا يمكنك إرسال تعليق فارغ!');
                    e.preventDefault();
                }
            });
        }
    </script>
</body>
</html>