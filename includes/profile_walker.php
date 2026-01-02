<?php
// Start the session to access logged-in user data
session_start();
// Connect to the database
require "db_connect.php";

// If the walker is not logged in, redirect to login page
if (!isset($_SESSION['walker_id'])) {
    header("Location: login_walker.php");
    exit;
}
// Get walker ID & name from session
$id = $_SESSION['walker_id'];
$walkerName = $_SESSION['walker_name'] ?? '';

//SQL query to fetch walker profile details
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
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/profile.css">
</head>

<body>

    <!--Loading header-->
    <div id="header-container">
        <?php include 'header_walker.php'; ?>
    </div>

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
            </div>
        </div>
    </div>

    <footer class="footer">
        <p>© 2025 SafeWalk</p>
    </footer>

</body>
</html>