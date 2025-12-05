<?php
session_start();

//connect to DB
require "db_connect.php";

// if no session - redirect to login
if (!isset($_SESSION['walker_id'])) {
    header("Location: login_walker.html");
    exit;
}

$walkerName = $_SESSION['walker_name'];

// Check online volunteers
$stmt = $conn->prepare("SELECT first_name, last_name, phone FROM volunteers WHERE is_online = 1");
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
    <title>שיחת טלפון - SafeWalk</title>
    <link rel="stylesheet" href="../css/phone_call_escort.css">
</head>
<body>

<header class="header">
    <img src="../images/logo.png" alt="SafeWalk Logo" class="logo">
    <h1>שיחת טלפון עם מתנדבת</h1>
</header>

<div class="container">
    <h1>שלום <?php echo htmlspecialchars($walkerName); ?> 🌟</h1>
    <h2>בחרי מתנדבת זמינה לשיחת טלפון</h2>

    <p>התקשרי למתנדבות הזמינות דרך הכפתורים הבאים:</p>

    <div class="volunteer-list">
        <?php if (count($volunteers) === 0): ?>
            <p>אין מתנדבות זמינות כרגע. אנא נסי מאוחר יותר.</p>
        <?php else: ?>
            <?php foreach ($volunteers as $volunteer): ?>
                <div class="volunteer-box">
                    <span class="name">
                        <?php echo htmlspecialchars($volunteer['first_name'] . ' ' . $volunteer['last_name']); ?>
                    </span>

                    <a href="tel:<?php echo preg_replace('/\D/', '', $volunteer['phone']); ?>" 
                       class="btn call-btn">
                        התקשרי
                    </a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <a href="Home_walker.php" class="back">חזרה לדף הבית</a>
</div>

<footer class="footer">
    <p>© 2025 SafeWalk</p>
</footer>

</body>
</html>
