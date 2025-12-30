<?php
session_start();
require "db_connect.php";

// הגנה – רק מתנדבת
if (!isset($_SESSION['volunteer_id'])) {
    header("Location: login_volunteer.php");
    exit;
}

// שליפת הולכות רגל מחוברות
$stmt = $conn->prepare("
    SELECT id, first_name, last_name, profile_image 
    FROM walkers 
    WHERE is_online = 1
");
$stmt->execute();
$result = $stmt->get_result();

$walkers = [];
while ($row = $result->fetch_assoc()) {
    $walkers[] = $row;
}

$volName = $_SESSION['volunteer_name'];

?>

<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>הולכות רגל מחוברות - SafeWalk</title>
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/online_walkers.css">
</head>
<body>

<!--Loading header-->
<div id="header-container">
    <?php include 'header_volunteer.php'; ?>
</div>

<div class="container">
    <h1>הולכות רגל מחוברות</h1>
    <h2 class="subtitle">בחרי הולכת רגל לצפייה במיקום</h2>

    <div class="volunteer-list">
        <?php if (count($walkers) === 0): ?>
            <p>אין כרגע הולכות רגל מחוברות.</p>
        <?php else: ?>
            <?php foreach ($walkers as $walker): ?>
                <div class="volunteer-box">

                    <img 
                        src="../uploads/profile_images/<?= $walker['profile_image'] ?: 'default.png' ?>" 
                        class="mini-profile"
                    >

                    <span class="name">
                        <?= htmlspecialchars($walker['first_name'] . ' ' . $walker['last_name']); ?>
                    </span>

                    <a 
                        href="view_walker_location.php?walker_id=<?= $walker['id']; ?>" 
                        class="btn"
                    >
                        צפייה במיקום
                    </a>

                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<footer class="footer">
    <p>© 2025 SafeWalk</p>
</footer>

</body>
</html>
