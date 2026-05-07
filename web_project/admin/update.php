<?php
require '../includes/auth.php';
require '../includes/db.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM regions WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$region = $stmt->get_result()->fetch_assoc();
if (!$region) die('السجل غير موجود');

// ✅ هذا السطر هو الإصلاح
$data = $region;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = trim($_POST['name'] ?? '');
    $category    = trim($_POST['category'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $location    = trim($_POST['location'] ?? '');
    $features    = trim($_POST['features'] ?? '');
    $activities  = trim($_POST['activities'] ?? '');
    $landmarks   = trim($_POST['landmarks'] ?? '');

    if (!$name || !$category || !$description) {
        $error = 'الرجاء ملء الحقول المطلوبة';
    } else {
        function uploadOrKeep($file, $old) {
            if ($file['error'] === 0) {
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $newName = uniqid() . '.' . $ext;
                move_uploaded_file($file['tmp_name'], "../uploads/" . $newName);
                return $newName;
            }
            return $old;
        }

        $main_image = uploadOrKeep($_FILES['main_image'],     $region['main_image']);
        $img1       = uploadOrKeep($_FILES['gallery_image1'], $region['gallery_image1']);
        $img2       = uploadOrKeep($_FILES['gallery_image2'], $region['gallery_image2']);
        $img3       = uploadOrKeep($_FILES['gallery_image3'], $region['gallery_image3']);

        $stmt = $conn->prepare("UPDATE regions SET 
            name=?, category=?, description=?, location=?, features=?,
            activities=?, landmarks=?, main_image=?, 
            gallery_image1=?, gallery_image2=?, gallery_image3=?
            WHERE id=?");
        $stmt->bind_param("sssssssssssi",
            $name, $category, $description, $location, $features,
            $activities, $landmarks, $main_image, $img1, $img2, $img3, $id
        );
        $stmt->execute();

        // بعد التحديث، حدّث $data بالبيانات الجديدة
        $data = $_POST;
        $data['name'] = $name;

        $_SESSION['msg'] = 'تم تحديث السجل بنجاح ✓';
        header('Location: dashboard.php');
        exit();
    }
    // عند وجود خطأ، استخدم POST
    $data = $_POST;
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تحديث المحتوى</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<nav>
    <span class="logo">🇸🇦 اكتشف السعودية</span>
    <div>
        <a href="dashboard.php">لوحة التحكم</a>
        <a href="../index.php">الصفحة الأولى</a>
        <a href="logout.php">تسجيل الخروج</a>
    </div>
</nav>

<div class="form-container">
    <h2>تحديث: <?= htmlspecialchars($region['name']) ?></h2>

    <?php if ($error): ?>
        <div class="alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">

        <div class="form-group">
            <label>اسم المكان *</label>
            <input type="text" name="name" value="<?= htmlspecialchars($data['name']) ?>" required>
        </div>

        <div class="form-group">
            <label>التصنيف *</label>
            <select name="category" required>
                <?php foreach(['وسطى','غربية','شرقية','جنوبية','شمالية','وسط'] as $cat): ?>
                    <option value="<?= $cat ?>" <?= $data['category'] === $cat ? 'selected' : '' ?>>
                        <?= $cat ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>الوصف *</label>
            <textarea name="description" rows="4" required><?= htmlspecialchars($data['description']) ?></textarea>
        </div>

        <div class="form-group">
            <label>الموقع</label>
            <input type="text" name="location" value="<?= htmlspecialchars($data['location'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label>المميزات</label>
            <textarea name="features"><?= htmlspecialchars($data['features'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label>الأنشطة</label>
            <textarea name="activities"><?= htmlspecialchars($data['activities'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label>المعالم</label>
            <input type="text" name="landmarks" value="<?= htmlspecialchars($data['landmarks'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label>الصورة الرئيسية (اتركها فارغة للإبقاء على الحالية)</label>
            <?php if ($region['main_image']): ?>
                <img src="../uploads/<?= htmlspecialchars($region['main_image']) ?>" 
                     style="height:80px; border-radius:6px; margin-bottom:8px; display:block">
            <?php endif; ?>
            <input type="file" name="main_image" accept="image/*">
        </div>

        <div class="form-group">
            <label>صورة المعرض 1</label>
            <input type="file" name="gallery_image1" accept="image/*">
        </div>

        <div class="form-group">
            <label>صورة المعرض 2</label>
            <input type="file" name="gallery_image2" accept="image/*">
        </div>

        <div class="form-group">
            <label>صورة المعرض 3</label>
            <input type="file" name="gallery_image3" accept="image/*">
        </div>

        <button type="submit" class="btn-green">حفظ التحديثات</button>
    </form>
</div>

<footer>© اكتشف السعودية — جامعة الملك سعود</footer>
<script src="../js/main.js"></script>
</body>
</html>
