<?php
session_start();

// אם אין סשן – שלח להתחברות
if (!isset($_SESSION['walker_id'])) {
    header("Location: login_walker.php");
    exit;
}

$walkerName = $_SESSION['walker_name'];
?>

<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SafeWalk - תמיכה ושאלות נפוצות</title>
    <link rel="stylesheet" href="../css/support_walker.css">
</head>
<body>

<header class="header-dashboard">
    <div class="dashboard-content-wrapper">
        <img src="../images/logo.png" alt="SafeWalk Logo" class="logo">
        <h2 class="welcome-text">תמיכה ושאלות נפוצות</h2>
    </div>
</header>

<div class="shell">
    <main class="support-content">

        <h3 class="section-title">❓ שאלות נפוצות</h3>

        <div class="faq-item">
            <button class="faq-question">איך פותחים קריאה חדשה? 📋</button>
            <div class="faq-answer">
                <p>במסך הבית לחצי על “פתיחת קריאה”, מלאי את היעד שלך ושמרי. המתנדבת תקבל את הקריאה ותחזור אלייך בהקדם.</p>
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question">תוך כמה זמן מתנדבת חוזרת אליי? ⏱️</button>
            <div class="faq-answer">
                <p>לרוב תוך מספר דקות. אם אין מענה, ניתן לנסות לשלוח שוב קריאה חדשה.</p>
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question">איך מבטלים קריאה? 🚫</button>
            <div class="faq-answer">
                <p>יש להיכנס להיסטוריית הקריאות ולבחור קריאה פעילה. שם אפשר לבצע ביטול.</p>
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question">מה לעשות במקרה חירום? 🚨</button>
            <div class="faq-answer">
                <p>תוכלי ללחוץ על כפתור ה־S.O.S במסך הראשי או להתקשר מיד למוקד 100.</p>
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question">האם השירות בתשלום? 💰</button>
            <div class="faq-answer">
                <p>לא. השירות ניתן בהתנדבות מלאה ע"י קהילת SafeWalk.</p>
            </div>
        </div>

        <h3 class="section-title">📞 צריכה עוד עזרה?</h3>

        <a href="tel:0500000000" class="support-call-btn">התקשרי לתמיכה</a>

        <button class="back-btn" onclick="window.location.href='home_walker.php'">⬅ חזרה לדף הבית</button>
    </main>
</div>

<footer class="footer">
    <p>© 2025 SafeWalk</p>
</footer>

<script src="../js/support_walker.js"></script>


</body>
</html>
