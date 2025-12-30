<?php
session_start();

//connect to DB
require "db_connect.php";

// if no session - redirect to login
if (!isset($_SESSION['walker_id'])) {
    header("Location: login_walker.php");
    exit;
}

$walkerName = $_SESSION['walker_name'];

// Check online volunteers
$stmt = $conn->prepare("SELECT first_name, last_name, phone, profile_image FROM volunteers WHERE is_online = 1");
$stmt->execute();
$result = $stmt->get_result();

$volunteers = [];
while ($row = $result->fetch_assoc()) {
    $volunteers[] = $row;
}
?>

<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>שיחת טלפון - SafeWalk</title>
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/phone_call_escort.css">
</head>
<body>
    <div id="header-container">
        <?php include 'header_walker.php'; ?>
    </div>

<div class="container">
    <h1>בחרי מתנדבת זמינה לשיחת טלפון</h1>
    <p>התקשרי למתנדבות הזמינות דרך הכפתורים הבאים:</p>

    <div class="volunteer-list">
        <?php if (count($volunteers) === 0): ?>
            <p>אין מתנדבות זמינות כרגע. אנא נסי מאוחר יותר.</p>
        <?php else: ?>
            <?php foreach ($volunteers as $volunteer): ?>
                <div class="volunteer-box">
                    <img src="../uploads/profile_images/<?= $volunteer['profile_image'] ?: 'default.png' ?>" class="mini-profile">
    
                    <span class="name">
                    <?= htmlspecialchars($volunteer['first_name'] . ' ' . $volunteer['last_name']); ?>
                    </span>

                    <a href="tel:<?= preg_replace('/\D/', '', $volunteer['phone']); ?>" class="btn call-btn"> התקשרי</a>
                </div>

            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <a href="home_walker.php" class="back">חזרה לדף הבית</a>
</div>

<footer class="footer">
    <p>© 2025 SafeWalk</p>
</footer>

</body>
</html>
