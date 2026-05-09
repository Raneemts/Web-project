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

        $_SESSION['msg'] = 'تمت إضافة السجل بنجاح ✓';
        header('Location: dashboard.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إضافة محتوى</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<nav class="admin-nav">
    <span>لوحة تحكم المشرف</span>
    <div>
        <button onclick="toggleNight()" class="admin-nav-link" id="nightBtn">🌙 الوضع الليلي</button>
        <a href="dashboard.php">لوحة التحكم</a>
        <a href="../index.php">الصفحة الأولى</a>
        <a href="logout.php" class="btn-logout-nav">تسجيل الخروج</a>
    </div>
</nav>

<div class="form-container">
    <h2 class="form-title">إضافة مكان جديد</h2>

    <?php if ($error): ?>
        <div class="alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">

        <div class="form-group">
            <label>*اسم المكان</label>
            <input type="text" name="name" placeholder="مثال: الرياض"
                   value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label>*الصورة الرئيسية للمكان</label>
            <input type="file" name="main_image" accept="image/*">
        </div>

        <div class="form-group">
            <label>*الوصف</label>
            <textarea name="description" placeholder="اكتب وصفًا تفصيليًا..."><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label>*الموقع</label>
            <select name="category">
                <option value="">اختر المنطقة</option>
                <option value="وسطى">وسطى</option>
                <option value="غربية">غربية</option>
                <option value="شرقية">شرقية</option>
                <option value="جنوبية">جنوبية</option>
                <option value="شمالية">شمالية</option>
            </select>
        </div>

        <div class="form-group">
            <label>*المميزات</label>
            <input type="text" name="features"
                   placeholder="مثال: موقع أثري، طبيعية فريدة">
        </div>

        <div class="form-group">
            <label>*الأنشطة</label>
            <input type="text" name="activities"
                   placeholder="مثال: جولات سياحية تاريخية">
        </div>

        <div class="form-group">
            <label>*المعالم (الأفضل بينها فاصلة)</label>
            <input type="text" name="landmarks"
                   placeholder="مثال: برج الملك فهد، قلعة تاريخية">
        </div>

        <p class="section-title">صور المعرض</p>

        <div class="form-group">
            <label>*صورة المعرض الأولى</label>
            <input type="file" name="gallery_image1" accept="image/*">
        </div>

        <div class="form-group">
            <label>صورة المعرض الثانية (اختياري)</label>
            <input type="file" name="gallery_image2" accept="image/*">
        </div>

        <div class="form-group">
            <label>صورة المعرض الثالثة (اختياري)</label>
            <input type="file" name="gallery_image3" accept="image/*">
        </div>

        <button type="submit" class="btn-green">إضافة المكان</button>

    </form>
</div>

<footer>© اكتشف السعودية — جامعة الملك سعود</footer>
<script src="../js/main.js"></script>
</body>
</html>
