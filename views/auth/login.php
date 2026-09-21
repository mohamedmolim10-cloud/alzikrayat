<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - Alzikrayat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; }
        .login-card { max-width: 420px; margin: 60px auto; border: none; border-radius: 12px; }
    </style>
</head>
<body>

<div class="container">
    <div class="card login-card shadow-sm p-4">
        <h3 class="text-center mb-4 text-primary fw-bold">تسجيل الدخول</h3>
       <!-- عرض كوكي آخر تسجيل دخول من هذا المتصفح إن وُجدت -->
        <?php if (isset($_COOKIE['last_login'])): ?>
            <div class="alert alert-info py-2 text-center" style="font-size: 0.9rem;">
                 <strong>Last login from this computer was:</strong><br>
                <?= htmlspecialchars($_COOKIE['last_login']) ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger py-2"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="/alzikrayat/public/login" method="POST">
            <div class="mb-3">
                <label class="form-label fw-semibold">البريد الإلكتروني</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">كلمة المرور</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">دخول</button>
        </form>

        <div class="text-center mt-3">
            <small class="text-muted">ليس لديك حساب؟ <a href="/alzikrayat/public/register" class="text-decoration-none fw-semibold">إنشاء حساب جديد</a></small>
        </div>
    </div>
</div>

</body>
</html>