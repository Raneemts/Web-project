<?php
session_start();
require 'includes/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $conn->prepare("SELECT * FROM regions WHERE id = ?");
$stmt->bind_param("i", $id);   
$stmt->execute();

$result = $stmt->get_result();
$region = $result->fetch_assoc();

if (!$region) {
    die("المنطقة غير موجودة");
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($region['name']) ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav>
    <span class="logo">🇸🇦 اكتشف السعودية</span>
    <div>
        <a href="index.php">الرئيسية</a>
        <a href="regions.php">معرض المناطق</a>
        <button id="nightBtn" onclick="toggleNight()">🌙 الوضع الليلي</button>
    </div>
</nav>

<div style="max-width:900px; margin:auto; padding:30px">

    <!-- MAIN IMAGE -->
    <?php if ($region['main_image']): ?>
    <img src="uploads/<?= htmlspecialchars($region['main_image']) ?>" 
         alt="" style="width:100%; height:400px; object-fit:cover; border-radius:12px; margin-bottom:20px">
    <?php endif; ?>

    <h1 style="font-size:2.5rem; color:var(--green)"><?= htmlspecialchars($region['name']) ?></h1>
    <p style="line-height:1.9; font-size:1.1rem; margin:15px 0"><?= htmlspecialchars($region['description']) ?></p>

    <!-- QUICK INFO -->
    <div class="card" style="padding:20px; margin:20px 0">
        <h2>معلومات سريعة</h2>
        <ul style="margin-top:10px; line-height:2.2">
            <?php if ($region['location']): ?>
                <li>📍 الموقع: <?= htmlspecialchars($region['location']) ?></li>
            <?php endif; ?>
            <?php if ($region['features']): ?>
                <li>✨ المميزات: <?= htmlspecialchars($region['features']) ?></li>
            <?php endif; ?>
            <?php if ($region['activities']): ?>
                <li>🎯 الأنشطة: <?= htmlspecialchars($region['activities']) ?></li>
            <?php endif; ?>
        </ul>
    </div>

    <!-- LANDMARKS -->
    <?php if ($region['landmarks']): ?>
    <div style="margin:20px 0">
        <h2>أبرز المعالم</h2>
        <ul style="margin-top:10px; line-height:2.2">
            <?php foreach (explode('،', $region['landmarks']) as $lm): ?>
                <li>🏛️ <?= htmlspecialchars(trim($lm)) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <!-- IMAGE GALLERY -->
    <h2>معرض الصور</h2>
    <div class="grid-3" style="padding:0; margin-top:15px">
        <?php foreach (['gallery_image1','gallery_image2','gallery_image3'] as $img): ?>
            <?php if ($region[$img]): ?>
            <img src="uploads/<?= htmlspecialchars($region[$img]) ?>" 
                 style="width:100%; height:180px; object-fit:cover; border-radius:8px">
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <a href="regions.php" style="display:inline-block; margin-top:30px" class="btn-green">
        ← العودة إلى المعرض
    </a>
</div>

<footer>© اكتشف السعودية — جامعة الملك سعود</footer>
<script src="js/main.js"></script>
</body>
</html>