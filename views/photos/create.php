<?php
// views/photos/create.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// التحقق من تسجيل الدخول (حماية الصفحة)
if (!isset($_SESSION['user'])) {
    header('Location: /alzikrayat/public/login');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الذكريات - رفع صورة جديدة</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- شريط التنقل -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/alzikrayat/public/photos">الذكريات</a>
            <div class="d-flex align-items-center gap-3">
                <span class="text-white">Hi <?= htmlspecialchars($_SESSION['user']['first_name']) ?></span>
                <a href="/alzikrayat/public/photos" class="btn btn-sm btn-outline-light">العودة للمعرض</a>
                <a href="/alzikrayat/public/logout" class="btn btn-sm btn-danger">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header bg-dark text-white text-center py-3">
                        <h4 class="m-0 fw-bold">مشاركة ذكرى جديدة 📷</h4>
                    </div>
                    <div class="card-body p-4">

                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger" role="alert">
                                <?= htmlspecialchars($error) ?>
                            </div>
                        <?php endif; ?>

                        <form id="uploadForm" action="/alzikrayat/public/upload" method="POST" enctype="multipart/form-data">
                            <!-- اختيار ملف الصورة -->
                            <div class="mb-3">
                                <label for="image" class="form-label fw-semibold">اختر الصورة <span class="text-danger">*</span></label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/png, image/jpeg, image/jpg, image/webp" required>
                                <div class="form-text">الصيغ المدعومة: JPG, PNG, WEBP (الحد الأقصى 5MB)</div>
                            </div>

                            <!-- عنوان الصورة -->
                            <div class="mb-3">
                                <label for="title" class="form-label fw-semibold">عنوان الصورة <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="title" name="title" maxlength="200" placeholder="مثال: رحلة التخرج، يوم في النيل..." required>
                            </div>

                            <!-- وصف الصورة -->
                            <div class="mb-4">
                                <label for="description" class="form-label fw-semibold">قصة أو وصف الصورة</label>
                                <textarea class="form-control" id="description" name="description" rows="4" placeholder="اكتب تفاصيل أو ذكرى لطيفة عن هذه اللحظة..."></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">نشر الصورة في المعرض</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- التحقق من جهة العميل Client-side JS Validation -->
    <script>
        document.getElementById('uploadForm').addEventListener('submit', function (e) {
            const fileInput = document.getElementById('image');
            const titleInput = document.getElementById('title');
            
            if (fileInput.files.length === 0) {
                alert('يرجى اختيار ملف الصورة أولاً!');
                e.preventDefault();
                return;
            }

            const file = fileInput.files[0];
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
            if (!allowedTypes.includes(file.type)) {
                alert('عذراً، يرجى اختيار ملف صورة صالح (JPEG, PNG, WEBP).');
                e.preventDefault();
                return;
            }

            if (file.size > 5 * 1024 * 1024) {
                alert('حجم الصورة كبير جداً! الحد الأقصى المسموح به هو 5 ميجابايت.');
                e.preventDefault();
                return;
            }

            if (titleInput.value.trim() === '') {
                alert('يرجى إدخال عنوان للصورة.');
                e.preventDefault();
                return;
            }
        });
    </script>
</body>
</html>