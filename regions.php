<?php
session_start();
require 'includes/db.php';

// Fetch all regions from DB
$result = $conn->query("SELECT * FROM regions");
$regions = $result->fetch_all(MYSQLI_ASSOC);

// Get unique categories for filter
$catResult = $conn->query("SELECT DISTINCT category FROM regions");
$cats = $catResult->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>معرض المناطق</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav>
    <span class="logo">🇸🇦 اكتشف السعودية</span>
    <div>
        <a href="index.php">الرئيسية</a>
        <a href="regions.php">معرض المناطق</a>
        <a href="admin/login.php">دخول المشرف</a>
        <button id="nightBtn" onclick="toggleNight()">🌙 الوضع الليلي</button>
    </div>
</nav>

<div style="padding:30px">
    <h1>معرض المناطق</h1>
    <p>ابحث أو صنّف النتائج ثم اضغط على أي منطقة للانتقال إلى صفحة التفاصيل.</p>

    <!-- SEARCH -->
    <input type="text" id="searchInput" placeholder="ابحث عن منطقة..."
           oninput="searchRegions()"
           style="padding:10px; width:300px; border-radius:8px; border:1px solid #ddd; margin:15px 0; direction:rtl">

    <!-- FILTER BUTTONS -->
    <div class="filter-bar">
        <button class="filter-btn active" onclick="filterRegions('all', event)">الكل</button>
        <?php foreach ($cats as $cat): ?>
            <button class="filter-btn" onclick="filterRegions('<?= htmlspecialchars($cat['category']) ?>', event)">
                <?= htmlspecialchars($cat['category']) ?>
            </button>
        <?php endforeach; ?>
    </div>

    <p>عدد النتائج: <strong id="count"><?= count($regions) ?></strong></p>

    <!-- GALLERY GRID -->
    <div class="grid-3" id="regionsGrid">
        <?php foreach ($regions as $r): ?>
        <div class="card region-card" data-category="<?= htmlspecialchars($r['category']) ?>"
             onclick="window.location='details.php?id=<?= $r['id'] ?>'">
            <?php if (!empty($r['main_image'])): ?>
                <img src="uploads/<?= htmlspecialchars($r['main_image']) ?>" 
                     alt="<?= htmlspecialchars($r['name']) ?>">
            <?php else: ?>
                <div style="height:200px; background:#2d6a4f; display:flex; 
                            align-items:center; justify-content:center;">
                    <span style="color:white; font-size:3rem;">🏙️</span>
                </div>
            <?php endif; ?>
            <div class="card-body">
                <small style="color:var(--gold)"><?= htmlspecialchars($r['category']) ?></small>
                <h3><?= htmlspecialchars($r['name']) ?></h3>
                <p style="font-size:0.9rem; color:#666">
                    <?= mb_substr($r['description'], 0, 80) ?>...
                </p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<footer>© اكتشف السعودية — جامعة الملك سعود</footer>

<script src="js/main.js"></script>
<script>
function searchRegions() {
    const query = document.getElementById('searchInput').value.toLowerCase();
    const cards = document.querySelectorAll('.region-card');
    let count = 0;
    cards.forEach(card => {
        const name = card.querySelector('h3').textContent.toLowerCase();
        if (name.includes(query)) {
            card.style.display = 'block';
            count++;
        } else {
            card.style.display = 'none';
        }
    });
    document.getElementById('count').textContent = count;
}
</script>
</body>
</html>