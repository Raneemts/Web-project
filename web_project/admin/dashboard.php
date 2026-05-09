<?php
require '../includes/auth.php';
require '../includes/db.php';

if (isset($_POST['delete_id'])) {
    $id = (int)$_POST['delete_id'];

    $s = $conn->prepare("SELECT main_image, gallery_image1, gallery_image2, gallery_image3 FROM regions WHERE id=?");
    $s->bind_param("i", $id);
    $s->execute();
    $row = $s->get_result()->fetch_assoc();

    $del = $conn->prepare("DELETE FROM regions WHERE id=?");
    $del->bind_param("i", $id);
    $del->execute();

    if ($row) {
        foreach ([$row['main_image'], $row['gallery_image1'], $row['gallery_image2'], $row['gallery_image3']] as $img) {
            if ($img && file_exists("../uploads/$img")) {
                unlink("../uploads/$img");
            }
        }
    }

    $_SESSION['msg'] = 'تم حذف السجل بنجاح ✓';
    header('Location: dashboard.php');
    exit();
}

$msg = $_SESSION['msg'] ?? '';
unset($_SESSION['msg']);

$regions = $conn->query("SELECT * FROM regions ORDER BY id ASC")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>لوحة التحكم</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="admin-nav">
    <span class="admin-brand">لوحة تحكم المشرف</span>
    <div class="admin-nav-links">
        <a href="../index.php" class="admin-nav-link">الصفحة الأولى</a>
        <a href="add.php" class="admin-nav-link admin-btn-add">إضافة جديد</a>
        <a href="logout.php" class="admin-nav-link admin-btn-logout">تسجيل الخروج</a>
    </div>
</div>

<div class="admin-content">

    <?php if ($msg): ?>
        <div class="alert-success"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <div class="page-title">
        <h1>إدارة المحتوى</h1>
        <p>استخدم هذه الصفحة لإدارة محتوى الموقع من خلال عرض السجلات وإضافة أو تعديل أو حذف المحتوى</p>
    </div>

    <a href="add.php" class="btn-add">+ إضافة سجل جديد</a>

    <table class="admin-table">
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
            <?php if (empty($regions)): ?>
                <tr>
                    <td colspan="5" class="empty-table">لا توجد سجلات بعد</td>
                </tr>
            <?php endif; ?>
            <?php $counter = 1; ?>
            <?php foreach ($regions as $r): ?>
            <tr>
                <td><?= $counter++ ?></td>
                <td><?= htmlspecialchars($r['name']) ?></td>
                <td><?= htmlspecialchars($r['category']) ?></td>
                <td><?= htmlspecialchars(mb_substr($r['description'], 0, 40)) ?>...</td>
                <td>
                    <div class="action-btns">
                        <a href="update.php?id=<?= $r['id'] ?>" class="btn-edit">تعديل</a>
                        <form method="POST" style="margin:0"
                              onsubmit="return confirm('هل تريد حذف هذا السجل؟')">
                            <input type="hidden" name="delete_id" value="<?= $r['id'] ?>">
                            <button type="submit" class="btn-delete">حذف</button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<footer>© اكتشف السعودية — جامعة الملك سعود</footer>
<script src="../js/main.js"></script>
</body>
</html>
