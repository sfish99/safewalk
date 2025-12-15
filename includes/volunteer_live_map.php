<?php
session_start();
require "db_connect.php";
require_once '../../config.php';

if (!isset($_SESSION['volunteer_id'])) {
    header("Location: login_volunteer.php");
    exit;
}

$volunteerName = $_SESSION['first_name'] ?? ($_SESSION['volunteer_name'] ?? 'מתנדבת');
?>
<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ליווי בזמן אמת - מתנדבת</title>
<link rel="stylesheet" href="../css/volunteer_live_map.css">
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_MAPS_API_KEY; ?>" async defer></script>
</head>
<body>
<header class="header">
  <h2>שלום <?php echo htmlspecialchars($volunteerName); ?> — ליווי בזמן אמת</h2>
</header>

<main>
  <div id="map"></div>
  <div class="info">
    <p id="statusText">טוען מיקום...</p>
  </div>
</main>

<footer class="footer">© SafeWalk</footer>

<script src="../js/volunteer_live_map.js"></script>
</body>
</html>
