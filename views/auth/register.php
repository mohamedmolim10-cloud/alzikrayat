<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء حساب جديد - Alzikrayat</title>
    <!-- Bootstrap 5 RTL -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; }
        .register-card { max-width: 550px; margin: 40px auto; border: none; border-radius: 12px; }
    </style>
</head>
<body>

<div class="container">
    <div class="card register-card shadow-sm p-4">
        <h3 class="text-center mb-4 text-primary fw-bold">إنشاء حساب جديد في معرض الذكريات</h3>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger py-2"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success py-2"><?= $success ?></div>
        <?php endif; ?>

        <form action="/alzikrayat/public/register" method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">الاسم الأول *</label>
                    <input type="text" name="first_name" class="form-control" required maxlength="50">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">اسم العائلة *</label>
                    <input type="text" name="last_name" class="form-control" required maxlength="50">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">البريد الإلكتروني *</label>
                <input type="email" name="email" class="form-control" required maxlength="100">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">كلمة المرور *</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">المدينة / الموقع </label>
                    <input type="text" name="location" class="form-control" maxlength="100">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">المهنة </label>
                    <input type="text" name="occupation" class="form-control" maxlength="100">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">نبذة عنك </label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">تسجيل الحساب</button>
        </form>

        <div class="text-center mt-3">
            <small class="text-muted">لديك حساب بالفعل؟ <a href="/alzikrayat/public/login" class="text-decoration-none fw-semibold">تسجيل الدخول</a></small>
        </div>
    </div>
</div>

</body>
</html>