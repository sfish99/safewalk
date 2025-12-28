<?php
session_start();
?>
<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>שגיאה בהרשמה - SafeWalk</title>
    <link rel="stylesheet" href="../css/register_result.css">
</head>
<body>

<header class="header">
    <a href="../index.html">
        <img src="../images/logo.png" alt="SafeWalk Logo" class="logo">
    </a>
</header>

<main class="main-content">
    <div class="result-box error">
        <h2>❌ שגיאה בהרשמה</h2>
        <p>
            אירעה תקלה בעת ההרשמה.<br>
            ייתכן שהאימייל כבר קיים או שחסרים פרטים.
        </p>

        <a href="../index.html" class="action-btn">
            מעבר למסך פתיחה
        </a>
    </div>
</main>

<footer class="footer">
    <p>© 2025 SafeWalk</p>
</footer>

</body>
</html>
