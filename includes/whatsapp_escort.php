<?php
session_start();

// בדיקת התחברות
if (!isset($_SESSION['volunteer_id'])) {
    header("Location: login_volunteer.html");
    exit;
}

$volunteerName = $_SESSION['volunteer_name'];
?>

<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>תיאום ליווי בוואטסאפ - SafeWalk</title>
    <link rel="stylesheet" href="../css/whatsapp_escort.css">
</head>
<body>

<div class="container">
    <h1>שלום <?php echo htmlspecialchars($volunteerName); ?> 🌟</h1>
    <h2>תיאום ליווי בוואטסאפ</h2>

    <p>כאן המתנדבת יכולה להתחבר לקבוצה / לקישור וואטסאפ כדי לנהל תקשורת עם מי שביקשה ליווי.</p>

    <div class="box">
        <label>קישור וואטסאפ לקבוצה / שיחה:</label>
        <input type="text" placeholder="הדביקי כאן קישור לוואטסאפ">
        <button class="btn">פתחי וואטסאפ</button>
    </div>

    <a href="Home_volunteer.php" class="back">חזרה לדף הבית</a>
</div>

</body>
</html>
