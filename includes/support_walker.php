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
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">    <title>SafeWalk - תמיכה ושאלות נפוצות</title>
    <link rel="stylesheet" href="../css/support.css">
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
                <button class="faq-question">מה הוא אתר <span dir="ltr">SafeWalk</span>? ℹ️</button>            <div class="faq-answer">
                <p> SafeWalk מציעה ליווי בזמן הליכה באמצעות שיחה טלפונית, וידאו, שיתוף מיקום, WhatsApp או ליווי AI, להגברת תחושת הביטחון. </p>
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question">איך פותחים קריאה חדשה? 📋</button>
            <div class="faq-answer">
                <p> במסך הבית לחצי על פתיחת קריאה ובחרי את סוג הליווי הרצוי. בליווי אנושי תוצג רשימת מתנדבות זמינות, ממנה תוכלי לבחור מתנדבת ולהתחיל איתה שיחה. בליווי מבוסס מיקום המתנדבת תתחבר למיקום שלך ותוכל לעקוב אחרייך עד ההגעה ליעד. בליווי AI השיחה מתחילה באופן מיידי עם מלווה קולי חכם, המלווה אותך בעל־פה לאורך ההליכה. </p>
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question">תוך כמה זמן מתנדבת חוזרת אליי? ⏱️</button>
            <div class="faq-answer">
                <p>לרוב תוך מספר דקות. אם אין מענה, ניתן לנסות לשלוח שוב קריאה חדשה.</p>
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question">מה קורה אם אף מתנדבת לא זמינה? 🚫</button>
            <div class="faq-answer">
                <p>  במידה ואין מתנדבת זמינה לליווי אנושי, ניתן לבחור בליווי AI  שמתחיל שיחה קולית באופן מיידי ומלווה אותך לאורך ההליכה. </p>
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

    </main>
</div>

<footer class="footer">
    <p>© 2025 SafeWalk</p>
</footer>

<script src="../js/support.js"></script>


</body>
</html>
