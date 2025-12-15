<?php
session_start();
require "db_connect.php";
require_once '../../config.php';

// בדוק שאדם מחובר כמתנדבת (volunteer)
if (!isset($_SESSION['volunteer_id'])) {
    header("Location: login_volunteer.php");
    exit;
}

$volunteerName = $_SESSION['first_name'] ?? ($_SESSION['volunteer_name'] ?? 'מתנדבת');
$walkId = isset($_GET['walk_id']) ? (int)$_GET['walk_id'] : 0;
if (!$walkId) {
    echo "יש לספק walk_id ב-URL";
    exit;
}
?>
<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>ליווי בזמן אמת - מתנדבת</title>
<link rel="stylesheet" href="../css/volunteer_live_map.css">
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_MAPS_API_KEY; ?>" async defer></script>
</head>
<body>
<header class="header">
  <div class="dashboard-content-wrapper">
    <img src="../images/logo.png" class="logo" alt="logo">
    <h2>שלום <?php echo htmlspecialchars($volunteerName); ?> — ליווי בזמן אמת</h2>
  </div>
</header>

<main class="shell">
  <div id="map"></div>
  <div class="info">
    <p id="statusText">טוען מיקום...</p>
  </div>
</main>

<footer class="footer">© SafeWalk</footer>

<script>const WATCHED_WALK_ID = <?php echo $walkId; ?>;</script>
<script src="../js/volunteer_live_map.js"></script>
</body>
</html>
