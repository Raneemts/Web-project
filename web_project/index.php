<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>اكتشف السعودية</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- NAVBAR -->
<nav>
    <span class="logo">🇸🇦 اكتشف السعودية</span>
    <div>
        <a href="index.php">الرئيسية</a>
        <a href="regions.php">معرض المناطق</a>
        <a href="admin/login.php">دخول المشرف</a>
        <button id="nightBtn" onclick="toggleNight()">🌙 الوضع الليلي</button>
    </div>
</nav>

<!-- HERO -->
<section style="display:flex; align-items:center; padding:60px 30px; gap:40px;">
    <div style="flex:1">
        <h1 style="font-size:2.5rem; color:var(--green);">موقع ثقافي تفاعلي للتعريف بالمملكة</h1>
        <p style="margin:20px 0; line-height:1.8;">
            استكشف مناطق المملكة العربية السعودية وتعرف على أهم المعالم التاريخية والثقافية.
            اختر منطقة من المعرض للانتقال إلى صفحة التفاصيل.
        </p>
        <a href="regions.php" class="btn-green" style="display:inline-block; text-decoration:none; padding:12px 30px; border-radius:8px;">
            ابدأ الاستكشاف
        </a>
    </div>
    <div style="flex:1; text-align:center; font-size:5rem;">🇸🇦<br><small style="font-size:1.5rem;">أهلاً بك</small></div>
</section>

<!-- INFO CARDS -->
<section class="grid-3">
    <div class="card">
        <div class="card-body">
            <h3>⭐ الهدف</h3>
            <p>تقديم معلومات عربية موثوقة عن مناطق المملكة وأبرز الوجهات.</p>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h3>🗺️ المناطق</h3>
            <p>معرض تفاعلي يتنقل بين المناطق بصور + عناوين + روابط.</p>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h3>📋 التفاصيل</h3>
            <p>صفحة تعرض وصفاً وصوراً ومعلومات تاريخية وثقافية عن الأماكن.</p>
        </div>
    </div>
</section>

<footer>© اكتشف السعودية — جامعة الملك سعود</footer>

<script src="js/main.js"></script>
</body>
</html>