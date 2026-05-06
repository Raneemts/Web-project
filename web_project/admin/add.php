<?php
require '../includes/auth.php';
require '../includes/db.php';

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

        function uploadImage($file) {
            if ($file['error'] === 0) {
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $newName = uniqid() . '.' . $ext;
                move_uploaded_file($file['tmp_name'], "../uploads/" . $newName);
                return $newName;
            }
            return null;
        }

        $main_image = uploadImage($_FILES['main_image']);
        $img1       = uploadImage($_FILES['gallery_image1']);
        $img2       = uploadImage($_FILES['gallery_image2']);
        $img3       = uploadImage($_FILES['gallery_image3']);

        $stmt = $conn->prepare("INSERT INTO regions 
            (name, category, description, location, features, activities, landmarks, 
             main_image, gallery_image1, gallery_image2, gallery_image3)
            VALUES (?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param("sssssssssss",
            $name, $category, $description, $location, $features,
            $activities, $landmarks, $main_image, $img1, $img2, $img3
        );
        $stmt->execute();

        $_SESSION['msg'] = 'تمت إضافة السجل بنجاح';
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
    <title>إضافة محتوى - اكتشف السعودية</title>
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
        <h2>إضافة مكان جديد</h2>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <?php foreach ($errors as $e): ?>
                    <div><?= htmlspecialchars($e) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group">
                    <label for="name">*اسم المكان</label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="مثال: المدينة" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="category">*النوع</label>
                    <select id="category" name="category" class="form-control" required>
                        <option value="">اختر المنطقة</option>
                        <option value="وسطى" <?= (($_POST['category'] ?? '') === 'وسطى') ? 'selected' : '' ?>>وسطى</option>
                        <option value="غربية" <?= (($_POST['category'] ?? '') === 'غربية') ? 'selected' : '' ?>>غربية</option>
                        <option value="شرقية" <?= (($_POST['category'] ?? '') === 'شرقية') ? 'selected' : '' ?>>شرقية</option>
                        <option value="جنوبية" <?= (($_POST['category'] ?? '') === 'جنوبية') ? 'selected' : '' ?>>جنوبية</option>
                        <option value="شمالية" <?= (($_POST['category'] ?? '') === 'شمالية') ? 'selected' : '' ?>>شمالية</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="description">*الوصف</label>
                <textarea id="description" name="description" class="form-control" placeholder="اكتب وصفًا تفصيليًا..." required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label for="location">*الموقع</label>
                <input type="text" id="location" name="location" class="form-control" placeholder="مثال: شمال المملكة العربية السعودية" value="<?= htmlspecialchars($_POST['location'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="features">*المميزات</label>
                <textarea id="features" name="features" class="form-control" placeholder="مثال: موقع أثري طبيعية فريدة&#10;(كل ميزة في سطر)"><?= htmlspecialchars($_POST['features'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label for="activities">*الأنشطة</label>
                <textarea id="activities" name="activities" class="form-control" placeholder="مثال: جولات سياحية تاريخية&#10;(كل نشاط في سطر)"><?= htmlspecialchars($_POST['activities'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label for="landmarks">*المعالم (الأفضل بينها مفاصلة)</label>
                <input type="text" id="landmarks" name="landmarks" class="form-control" placeholder="مثال: برج الملك فهد، قلعة تاريخية، الدرعية" value="<?= htmlspecialchars($_POST['landmarks'] ?? '') ?>">
            </div>

            <div class="form-section-title">صور المعرض</div>

            <div class="form-group">
                <label for="main_image">*الصورة الرئيسية للمكان</label>
                <input type="file" id="main_image" name="main_image" class="form-control" accept="image/*">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>صورة المعرض الثانية (اختياري)</label>
                    <input type="file" name="gallery_image1" class="form-control" accept="image/*">
                </div>
                <div class="form-group">
                    <label>صورة المعرض الثالثة (اختياري)</label>
                    <input type="file" name="gallery_image2" class="form-control" accept="image/*">
                </div>
            </div>

            <div class="form-group">
                <label>صورة المعرض الرابعة (اختياري)</label>
                <input type="file" name="gallery_image3" class="form-control" accept="image/*">
            </div>

            <button type="submit" class="btn-submit">إضافة المكان</button>
        </form>
    </div>
</div>

<footer class="footer">
    <p>© اكتشف السعودية — جامعة الملك سعود</p>
</footer>

<script src="../js/main.js"></script>
</body>
</html>
