<?php
session_start();
require "db_connect.php";
require_once '../../config.php';


// בדיקת סשן הולכת רגל
if (!isset($_SESSION['walker_id'])) {
    header("Location: login_walker.php");
    exit;
}

$walkerId = $_SESSION['walker_id'];


?>
<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>שיתוף מיקום - הולכת רגל</title>
<link rel="stylesheet" href="../css/walker_share_location.css">
<!-- מפה + הרחבה של Places לא נחוצה כאן כי לא עושים גיאוקודינג -->
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_MAPS_API_KEY; ?>" async defer></script>
</head>
<body>
<header class="header">
  <div class="dashboard-content-wrapper">
    <img src="../images/logo.png" class="logo" alt="logo">
    <h2>שלום <?php echo htmlspecialchars($_SESSION['walker_name'] ?? ''); ?> — שיתוף מיקום</h2>
  </div>
</header>

<main class="shell">
  <div class="map-wrap">
    <div id="map"></div>
  </div>

  <div class="controls">
    <button id="startBtn">התחלי שיתוף מיקום</button>
    <button id="stopBtn" disabled>הפסיקי שיתוף</button>
    <p class="note">המערכת תשלח את המיקום לשרת כל 5 שניות.</p>
  </div>
</main>

<footer class="footer">© SafeWalk</footer>

<script>
  const WALKER_ID = <?php echo (int)$walkerId; ?>;
</script>

<script src="../js/walker_share_location.js"></script>
</body>
</html>
