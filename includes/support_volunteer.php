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
            <button class="faq-question">איך אני מקבלת קריאה חדשה לליווי? 📲</button>
            <div class="faq-answer">
                <p>כדי לקבל קריאה, חשוב להיות זמינה באתר.</p>
                <p> כאשר משתמשת בוחרת בך לליווי, תקבלי ממנה פנייה חיצונית דרך שיחה, הודעה או שיחת וידאו.
                    הליווי עצמו לרוב אינו מתבצע דרך האתר. </p>
                <p> אם המשתמשת מבקשת ליווי מבוסס מיקום, לחצי על "מעקב מיקום" בעמוד הבית ובחרי את המשתמשת שתרצי לעקוב אחריה. </p>
            </div>
        </div>

          <div class="faq-item">
            <button class="faq-question">איך אני מסיימת ליווי? 🚫</button>
            <div class="faq-answer">
                <p>מנתקת את השיחה או מסיימת את הצ’אט, מוודאת שהמשתמשת סיימה ואינה זקוקה לעזרה נוספת, ולאחר מכן ממלאת את טופס סיום הליווי</p>
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question">האם אני צריכה להיות זמינה כל הזמן? ⏰</button>
            <div class="faq-answer">
                <p>לא. הזמינות נקבעת על ידך וניתנת לשינוי בכל רגע.</p>
                <p>קריאות יישלחו אלייך רק כאשר את מחוברת לאתר ומסומנת כזמינה.</p>
                <p> כאשר אינך מחוברת או שאינך זמינה, לא יישלחו אלייך קריאות.</p>
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question"> מתי נדרש ללחוץ על כפתור ה‑ SOS? 🆘</button>
            <div class="faq-answer">
                <p>לחצי על כפתור ה-SOS כאשר עולה חשש לשלום המשתמשת, אם היא נשמעת במצוקה, מפסיקה להגיב באמצע שיחה או התכתבות, או אם המיקום נכבה ללא התרעה ולא ניתן ליצור איתה קשר בשום אופן..</p>
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question"> האם אני רואה את המיקום של המשתמשת? 📍</button>
            <div class="faq-answer">
                <p>רק אם המשתמשת ביקשה ליווי מבוסס מיקום ואישרת זאת.
                    המעקב פעיל רק בזמן הליווי.</p>
            </div>
        </div>

              <div class="faq-item">
            <button class="faq-question"> האם הפרטים שלי גלויים למשתמשות? 🔒</button>
            <div class="faq-answer">
                <p>רק מידע בסיסי ורלוונטי לליווי.
                    פרטים אישיים נשארים חסויים.</p>
            </div>
        </div>

                    <div class="faq-item">
            <button class="faq-question">  צריכה עוד עזרה? 💬</button>
            <div class="faq-answer">
                <p>בכל שאלה, התלבטות או מצב חריג, ניתן לפנות לרכזת הקהילתית האזורית שלך לקבלת ליווי והכוונה./p>
            </div>
        </div>

            <div class="bottom-actions">
        <button class="back-home-btn" onclick="window.location.href='home_volunteer.php'">
            ⬅ חזרה לדף הבית
        </button>
    </div>

  
    </main>
</div>

<footer class="footer">
    <p>© 2025 SafeWalk</p>
</footer>

<script src="../js/support.js"></script>
</body>
</html>
