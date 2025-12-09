<?php
session_start();


if (!isset($_SESSION['volunteer_id'])) {
    header("Location: login_volunteer.php");
    exit;
}

$volunteerName = $_SESSION['volunteer_name'];
?>

<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SafeWalk - תמיכה ושאלות נפוצות למתנדבת</title>
    <link rel="stylesheet" href="../css/support.css">
</head>
<body>

<header class="header-dashboard">
    <div class="dashboard-content-wrapper">
        <img src="../images/logo.png" alt="SafeWalk Logo" class="logo">
        <h2 class="welcome-text">תמיכה ושאלות נפוצות - מתנדבת</h2>
        <p class="subtext">שלום <?php echo htmlspecialchars($volunteerName); ?> 🌟</p>
    </div>
</header>

<div class="shell">
    <main class="support-content">

        <h3 class="section-title">❓ שאלות נפוצות למתנדבות</h3>

        <div class="faq-item">
            <button class="faq-question">איך אני מקבלת קריאה חדשה? 📲</button>
            <div class="faq-answer">
                <p>כשתתקבל קריאה חדשה תתקבל התראה באפליקציה/אתר. היכנסי ל'היסטוריית קריאות' ובחרי באפשרות 'לקחת קריאה' כדי להתחייב.</p>
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question">מה קורה אם אני לא יכולה להגיע? ❌</button>
            <div class="faq-answer">
                <p>בטלי את ההקצאה דרך דף הקריאה כדי שתוכל/י לשחרר את הקריאה עבור מתנדבת אחרת. אם מדובר במקרי חירום עדכני את המנהל שממונה על המשמרת.</p>
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question">איך מתחברים לשעת משמרת? ⏰</button>
            <div class="faq-answer">
                <p>יש להשתמש בדאשבורד המשמרות במערכת ולהסמיך את עצמך למשמרת הרצויה. פרטים נוספים בדף 'משמרות'.</p>
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question">מה עושים במקרה בעיית תקשורת בזמן הולכה? 📶</button>
            <div class="faq-answer">
                <p>הפעילו את כפתור ה-S.O.S, ונסו ליצור קשר טלפוני עם הנקראת/הלקוח. לאחר מכן דווחו על הבעיה במערכת כדי שנוכל לבדוק ולשפר.</p>
            </div>
        </div>

        <h3 class="section-title">📞 צריכה עוד עזרה?</h3>

        <!-- row with two equal buttons -->
        <div class="btn-row">
            <a href="tel:0500000000" class="support-action-btn">התקשרי לתמיכה</a>
            <button class="support-action-btn" onclick="window.location.href='home_volunteer.php'">⬅ חזרה לדף הבית</button>
        </div>

    </main>
</div>

<footer class="footer">
    <p>© 2025 SafeWalk</p>
</footer>

<script src="../js/support.js"></script>
</body>
</html>
