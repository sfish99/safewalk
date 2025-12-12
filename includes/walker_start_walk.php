<?php
session_start();
require "db_connect.php";

if (!isset($_SESSION['walker_id'])) {
    header("Location: login_walker.php");
    exit;
}

$walkerId = $_SESSION['walker_id'];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $destination_lat = $_POST['destination_lat'];
    $destination_lng = $_POST['destination_lng'];

    $stmt = $conn->prepare("INSERT INTO walk_requests (walker_id, destination_lat, destination_lng, status, started_at) VALUES (?, ?, ?, 'active', NOW())");
    $stmt->bind_param("idd", $walkerId, $destination_lat, $destination_lng);
    if ($stmt->execute()) {
        $walkId = $stmt->insert_id;
        $_SESSION['current_walk_id'] = $walkId;
        header("Location: walker_walk_map.php");
        exit;
    } else {
        $error = "שגיאה ביצירת ההליכה";
    }
}
?>

<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
<meta charset="UTF-8">
<title>התחלת הליכה</title>
</head>
<body>
<h2>הזיני כתובת יעד</h2>

<?php if($error) echo "<p style='color:red;'>$error</p>"; ?>

<form method="POST">
    <label>קו רוחב (latitude):</label>
    <input type="text" name="destination_lat" required><br>
    <label>קו אורך (longitude):</label>
    <input type="text" name="destination_lng" required><br>
    <button type="submit">התחל הליכה</button>
</form>
</body>
</html>
