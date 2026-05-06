<?php
require '../includes/auth.php';
require '../includes/db.php';

// Handle delete
if (isset($_POST['delete_id'])) {
    $id = (int)$_POST['delete_id'];
    $stmt = $conn->prepare("DELETE FROM regions WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $_SESSION['msg'] = 'تم حذف السجل بنجاح';
    header('Location: dashboard.php');
    exit();
}

// Get success message
$msg = $_SESSION['msg'] ?? '';
unset($_SESSION['msg']);

$result = $conn->query("SELECT * FROM regions ORDER BY id DESC");
$regions = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - اكتشف السعودية</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<nav class="admin-navbar">
    <span class="brand">لوحة تحكم المشرف</span>
    <nav>
        <a href="../index.php">الصفحة الأولى</a>
        <a href="add.php" class="btn-add">إضافة جديد</a>
        <a href="logout.php" class="btn-logout">تسجيل الخروج</a>
    </nav>
</nav>

<div class="admin-container">
    
    <?php if (!empty($message)): ?>
        <div class="alert alert-<?= $messageType === 'success' ? 'success' : 'error' ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <div class="admin-header">
        <h1>إدارة المحتوى</h1>
        <p>استخدم هذه الصفحة لإدارة محتوى الموقع من خلال عرض السجلات وإضافة أو تعديل أو حذف المحتوى</p>
    </div>

    <a href="add.php" class="btn-add-new">+ إضافة سجل جديد</a>

    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>المنطقة</th>
                <th>التصنيف</th>
                <th>الوصف</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($regions) === 0): ?>
                <tr><td colspan="5" style="text-align:center; color:#999; padding:30px;">لا توجد سجلات بعد</td></tr>
            <?php endif; ?>
            <?php foreach ($regions as $region): ?>
            <tr>
                <td><?= $region['id'] ?></td>
                <td><?= htmlspecialchars($region['name']) ?></td>
                <td><?= htmlspecialchars($region['category']) ?></td>
                <td><?= htmlspecialchars(mb_substr($region['description'], 0, 50)) ?>...</td>
                <td>
                    <a href="update.php?id=<?= $region['id'] ?>" class="btn-edit">تعديل</a>
                    <button class="btn-delete" onclick="confirmDelete(<?= $region['id'] ?>)">حذف</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<footer class="footer">
    <p>© اكتشف السعودية — جامعة الملك سعود</p>
</footer>

<script src="../js/main.js"></script>
</body>
</html>
