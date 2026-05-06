<?php
session_start();
if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit();
}

require '../includes/db.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!$username || !$password) {
        $error = 'الرجاء إدخال اسم المستخدم وكلمة المرور';
    } else {
        $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $admin = $stmt->get_result()->fetch_assoc();

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['username'];
            header('Location: dashboard.php');
            exit();
        } else {
            $error = 'اسم المستخدم أو كلمة المرور غير صحيحة';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل دخول المشرف - اكتشف السعودية</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<nav class="admin-navbar">
    <span class="brand">لوحة المشرف</span>
    <nav>
        <a href="../index.php">زيارة الموقع</a>
        <a href="../index.php">الصفحة الأولى</a>
    </nav>
</nav>

<div class="login-page">
    <div class="login-box">
        <h2>تسجيل دخول المشرف</h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="username">اسم المستخدم</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    class="form-control"
                    placeholder="مثال: admin"
                    value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                    required
                >
            </div>
            <div class="form-group">
                <label for="password">كلمة المرور</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control"
                    placeholder="••••••••"
                    required
                >
            </div>
            <button type="submit" class="btn-submit">دخول</button>
        </form>

        <p style="text-align:center; margin-top:15px; font-size:0.8rem; color:#999;">
            بيانات الدخول الافتراضية: admin / admin123
        </p>
    </div>
</div>

<script src="../js/main.js"></script>
</body>
</html>
