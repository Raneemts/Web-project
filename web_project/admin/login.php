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
    <title>تسجيل دخول المشرف</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="login-page-bg">

<nav class="admin-nav">
    <button onclick="toggleNight()" class="admin-nav-link" id="nightBtn">🌙 الوضع الليلي</button>
    <span>لوحة المشرف</span>
    <div>
        <a href="../index.php">زيارة الموقع</a>
    </div>
</nav>

<div class="login-center">
    <div class="login-box">
        <h2>تسجيل دخول المشرف</h2>

        <?php if ($error): ?>
            <div class="alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>اسم المستخدم</label>
                <input type="text" name="username"
                       placeholder="مثال: admin"
                       value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>كلمة المرور</label>
                <input type="password" name="password" placeholder="••••••••">
            </div>
            <button type="submit" class="btn-submit">دخول</button>
        </form>
    </div>
</div>
<script src="../js/main.js"></script>
</body>
</html>
