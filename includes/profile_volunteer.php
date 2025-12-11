<?php
session_start();
require "../includes/db_connect.php";

if (!isset($_SESSION['volunteer_id'])) {
    header("Location: login_volunteer.php");
    exit;
}

$id = $_SESSION['volunteer_id'];

$stmt = $conn->prepare("SELECT first_name, last_name, email, phone, profile_image FROM volunteers WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>פרופיל מתנדבת - SafeWalk</title>
    <link rel="stylesheet" href="../css/profile.css">
</head>

<body>

<div class="profile-container">

    <h2 class="profile-title">הפרופיל שלי</h2>

    <div class="profile-image-wrapper">
        <img src="../uploads/profile_pics/<?= $result['profile_image'] ?: 'default.png' ?>" class="profile-image">
        <form action="update_profile.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="user_type" value="volunteer">
            <input type="file" name="profile_image" class="file-input">
    </div>

    <div class="profile-form">

        <label>שם פרטי</label>
        <input type="text" name="first_name" value="<?= $result['first_name'] ?>">

        <label>שם משפחה</label>
        <input type="text" name="last_name" value="<?= $result['last_name'] ?>">

        <label>אימייל</label>
        <input type="email" name="email" value="<?= $result['email'] ?>">

        <label>טלפון</label>
        <input type="text" name="phone" value="<?= $result['phone'] ?>">

        <button class="save-btn" type="submit">שמירה</button>
        </form>

        <button class="back-btn" onclick="window.location.href='home_volunteer.php'">חזרה</button>

    </div>

</div>

</body>
</html>
