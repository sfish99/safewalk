<?php
session_start();
require "../includes/db_connect.php";

if (!isset($_SESSION['walker_id'])) {
    header("Location: login_walker.php");
    exit;
}

$id = $_SESSION['walker_id'];

$stmt = $conn->prepare("SELECT first_name, last_name, email, phone, profile_image FROM walkers WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>הפרופיל שלי - SafeWalk</title>
    <link rel="stylesheet" href="../css/profile.css">
</head>

<body>

    <header class="header-dashboard">
        <div class="dashboard-content-wrapper">
            <img src="../images/logo.png" alt="SafeWalk Logo" class="logo">
        </div>
    </header>

    <div class="shell">
        <div class="profile-container">
            <h2 class="profile-title">הפרופיל שלי</h2>

            <div class="profile-image-section">
                <img src="../uploads/profile_images/<?= $result['profile_image'] ?: 'default.png' ?>" class="profile-image">
                
                <form action="update_profile.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="user_type" value="walker">
                    <div class="file-upload-wrapper">
                        <label for="profile_image" class="custom-file-upload">שינוי תמונת פרופיל</label>
                        <input type="file" id="profile_image" name="profile_image" class="file-input">
                    </div>
            </div>

            <div class="profile-form">
                <div class="input-group">
                    <label>שם פרטי</label>
                    <input type="text" name="first_name" value="<?= htmlspecialchars($result['first_name']) ?>">
                </div>

                <div class="input-group">
                    <label>שם משפחה</label>
                    <input type="text" name="last_name" value="<?= htmlspecialchars($result['last_name']) ?>">
                </div>

                <div class="input-group">
                    <label>אימייל</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($result['email']) ?>">
                </div>

                <div class="input-group">
                    <label>טלפון</label>
                    <input type="text" name="phone" value="<?= htmlspecialchars($result['phone']) ?>">
                </div>

                <button class="save-btn" type="submit">שמירת שינויים</button>
                </form>

                <button class="back-btn" onclick="window.location.href='home_walker.php'">חזרה לתפריט</button>
            </div>
        </div>
    </div>

    <footer class="footer">
        <p>© 2025 SafeWalk</p>
    </footer>

</body>
</html>