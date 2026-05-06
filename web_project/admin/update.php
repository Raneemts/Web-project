<?php
require '../includes/auth.php';
require '../includes/db.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM regions WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$region = $stmt->get_result()->fetch_assoc();
if (!$region) die('السجل غير موجود');

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

        $main_image = uploadOrKeep($_FILES['main_image'],      $region['main_image']);
        $img1       = uploadOrKeep($_FILES['gallery_image1'],  $region['gallery_image1']);
        $img2       = uploadOrKeep($_FILES['gallery_image2'],  $region['gallery_image2']);
        $img3       = uploadOrKeep($_FILES['gallery_image3'],  $region['gallery_image3']);

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

        $_SESSION['msg'] = 'تم تحديث السجل بنجاح';
        header('Location: dashboard.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تحديث المحتوى - اكتشف السعودية</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<nav class="admin-navbar">
    <span class="brand">لوحة تحكم المشرف</span>
    <nav>
        <a href="dashboard.php">لوحة التحكم</a>
        <a href="../index.php">الصفحة الأولى</a>
        <a href="logout.php" class="btn-logout">تسجيل الخروج</a>
    </nav>
</nav>

<div class="admin-container">
    <div class="admin-form-card">
        <h2>
            تحديث مكان
            <small style="font-size:0.7em; color:#666; display:block; margin-top:4px;">
                 المكان المحدد للتحديث: <?= htmlspecialchars($region['name']) ?>
            </small>
        </h2>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <?php foreach ($errors as $e): ?>
                    <div><?= htmlspecialchars($e) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <!-- المكان المحدد للتحديث -->
            <div style="background:#f0f9f4; border:1px solid #c3e6cb; border-radius:6px; padding:12px 15px; margin-bottom:20px; font-size:0.9rem; color:#155724;">
                المكان الحالي المحدد للتحديث: <strong><?= htmlspecialchars($region['name']) ?></strong>
            </div>

            <div class="form-section-title">تعديل البيانات</div>

            <div class="form-row">
                <div class="form-group">
                    <label for="name">اسم المكان</label>
                    <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($data['name']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="category">النوع</label>
                    <select id="category" name="category" class="form-control" required>
                        <option value="وسطى" <?= $data['category'] === 'وسطى' ? 'selected' : '' ?>>وسطى</option>
                        <option value="غربية" <?= $data['category'] === 'غربية' ? 'selected' : '' ?>>غربية</option>
                        <option value="شرقية" <?= $data['category'] === 'شرقية' ? 'selected' : '' ?>>شرقية</option>
                        <option value="جنوبية" <?= $data['category'] === 'جنوبية' ? 'selected' : '' ?>>جنوبية</option>
                        <option value="شمالية" <?= $data['category'] === 'شمالية' ? 'selected' : '' ?>>شمالية</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="description">الوصف</label>
                <textarea id="description" name="description" class="form-control" required><?= htmlspecialchars($data['description']) ?></textarea>
            </div>

            <div class="form-group">
                <label for="location">الموقع</label>
                <input type="text" id="location" name="location" class="form-control" value="<?= htmlspecialchars($data['location'] ?? '') ?>">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="features">المميزات</label>
                    <textarea id="features" name="features" class="form-control"><?= htmlspecialchars($data['features'] ?? '') ?></textarea>
                </div>
                <div class="form-group">
                    <label for="activities">الأنشطة</label>
                    <textarea id="activities" name="activities" class="form-control"><?= htmlspecialchars($data['activities'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="form-group">
                <label for="landmarks">المعالم الأفضل بينها مفاصلة</label>
                <input type="text" id="landmarks" name="landmarks" class="form-control" value="<?= htmlspecialchars($data['landmarks'] ?? '') ?>">
            </div>

            <!-- الصور الحالية -->
            <div class="form-section-title">الصورة الرئيسية الحالية</div>
            <?php if (!empty($region['main_image']) && file_exists('../uploads/images/' . $region['main_image'])): ?>
                <div style="margin-bottom:10px;">
                    <img src="../uploads/images/<?= htmlspecialchars($region['main_image']) ?>" style="height:80px; border-radius:6px; object-fit:cover;">
                </div>
            <?php else: ?>
                <p style="color:#999; font-size:0.85rem; margin-bottom:10px;">لا توجد صورة رئيسية حالية</p>
            <?php endif; ?>

            <div class="form-section-title">تحديث صور المعرض (اختياري)</div>
            <div class="form-row">
                <div class="form-group">
                    <label>تحديث الصورة الرئيسية (اختياري)</label>
                    <input type="file" name="main_image" class="form-control" accept="image/*">
                </div>
                <div class="form-group">
                    <label>صورة المعرض الأولى</label>
                    <input type="file" name="gallery_image1" class="form-control" accept="image/*">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>صورة المعرض الثانية</label>
                    <input type="file" name="gallery_image2" class="form-control" accept="image/*">
                </div>
                <div class="form-group">
                    <label>صورة المعرض الثالثة</label>
                    <input type="file" name="gallery_image3" class="form-control" accept="image/*">
                </div>
            </div>

            <!-- صور المعرض الحالية -->
            <?php
            $galleryImgs = array_filter([
                $region['gallery_image1'],
                $region['gallery_image2'],
                $region['gallery_image3']
            ]);
            if (count($galleryImgs) > 0):
            ?>
            <div class="form-section-title">صور المعرض الحالية</div>
            <div style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:15px;">
                <?php foreach ($galleryImgs as $img): ?>
                    <?php if (file_exists('../uploads/images/' . $img)): ?>
                        <img src="../uploads/images/<?= htmlspecialchars($img) ?>" style="height:70px; border-radius:6px; object-fit:cover;">
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <button type="submit" class="btn-submit">حفظ التحديثات</button>
        </form>
    </div>
</div>

<footer class="footer">
    <p>© اكتشف السعودية — جامعة الملك سعود</p>
</footer>

<script src="../js/main.js"></script>
</body>
</html>
