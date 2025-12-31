<?php
session_start();
//connect to DB
require "db_connect.php";
// API key folder
require_once '../../config.php';

// if there is no session - send to log in page
if (!isset($_SESSION['walker_id'])) {
    header("Location: login_walker.php");
    exit;
}
// Get walker id from session
$walkerId = $_SESSION['walker_id'];
$walkerName = $_SESSION['walker_name'] ?? '';


?>
<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>שיתוף מיקום - הולכת רגל</title>
  <link rel="stylesheet" href="../css/header.css"/>
  <link rel="stylesheet" href="../css/walker_share_location.css">
  <!-- Google Maps JavaScript API -->
  <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_MAPS_API_KEY; ?>" async defer></script>
</head>
<body>
  <!-- כאן ייטען ה-header -->
  <div id="header-container">
    <?php include 'header_walker.php'; ?>
  </div>

<main class="shell">
  <div class="map-wrap">
    <!-- Google Map will be rendered here -->
    <div id="map"></div>
  </div>

  <div class="controls">
    <button id="startBtn">התחלי שיתוף מיקום</button>
    <button id="stopBtn" disabled>הפסיקי שיתוף</button>
    <p class="note">המערכת תשלח את המיקום לשרת כל 5 שניות.</p>
  </div>

   <!-- SOS button -->
   <div class="sos-wrap">
       <a href="tel:100" class="sos-btn">S.O.S</a>
  </div>
</main>

<footer class="footer">© SafeWalk</footer>

<!-- Pass walker ID from PHP to JavaScript -->
<script>
  const WALKER_ID = <?php echo (int)$walkerId; ?>;
</script>

<!-- Main JavaScript file that handles map and location sharing -->
<script src="../js/walker_share_location.js"></script>
</body>
</html>
