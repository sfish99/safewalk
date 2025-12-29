<?php
session_start();

//connect to DB
require "db_connect.php";

// if there is no session - send to log in page
if (!isset($_SESSION['walker_id'])) {
    header("Location: login_walker.php");
    exit;
}

// Get walker name from session
$walkerName = $_SESSION['walker_name'];

// This function create the number for whatsApp
function formatPhoneForWhatsApp($phone) {
    //leave only digits
    $num = preg_replace('/\D/', '', $phone);

    // if begin with zero replace with 972
    if (strpos($num, '0') === 0) {
        $num = '972' . substr($num, 1);
    }

    return $num;
}

// Fetch online volunteers from the database
$stmt = $conn->prepare("SELECT first_name, last_name, phone, profile_image FROM volunteers WHERE is_online = 1");
$stmt->execute();
$result = $stmt->get_result();

// Store volunteers in an array
$volunteers = [];
while ($row = $result->fetch_assoc()) {
    $volunteers[] = $row;
}
?>

<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <title>ליווי בוואטסאפ - SafeWalk</title>
    <link rel="stylesheet" href="../css/whatsapp_escort.css">
</head>
<body>


<header class="header">
    <img src="../images/logo.png" alt="SafeWalk Logo" class="logo">
    <h1>ליווי בוואטסאפ</h1>
</header>
<main>
<div class="container">
    <h1>שלום <?php echo htmlspecialchars($walkerName); ?> 🌟</h1>
    <h2>בחרי מתנדבת זמינה לוואטסאפ</h2>

    <p>ניתן לפנות למתנדבות הזמינות דרך הקישורים הבאים:</p>

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
                    <!-- WhatsApp contact button -->
                    <a href="https://wa.me/<?= formatPhoneForWhatsApp($volunteer['phone']); ?>" target="_blank" class="btn">פתחי וואטסאפ</a>
                    </div>
                <?php endforeach; ?>

        <?php endif; ?>
    </div>

    <a href="home_walker.php" class="back">חזרה לדף הבית</a>
</div>

</main>
<footer class="footer">
    <p>© 2025 SafeWalk</p>
</footer>

</body>
</html>
