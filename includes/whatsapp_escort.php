<?php
session_start();

//connect to DB
require "db_connect.php";

// if there is no session - send to log in page
if (!isset($_SESSION['walker_id'])) {
    header("Location: login_walker.html");
    exit;
}

$walkerName = $_SESSION['walker_name'];

// Check if volunteers availables ,if someone available it will take her from DB
$stmt = $conn->prepare("SELECT first_name, last_name, phone_number FROM volunteers WHERE is_online = 1");
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
    <title>ליווי בוואטסאפ - SafeWalk</title>
    <link rel="stylesheet" href="../css/whatsapp_escort.css">
</head>
<body>

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
                    <span class="name"><?php echo htmlspecialchars($volunteer['first_name'] . ' ' . $volunteer['last_name']); ?></span>
                    <a href="https://wa.me/<?php echo preg_replace('/\D/', '', $volunteer['phone_number']); ?>" target="_blank" class="btn">פתחי וואטסאפ</a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <a href="Home_walker.php" class="back">חזרה לדף הבית</a>
</div>

</body>
</html>
