<?php
session_start();

// Check if walker connected
if (!isset($_SESSION['walker_id'])) {
    header("Location: login_walker.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ליווי במסלול - SafeWalk</title>
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<link rel="stylesheet" href="../css/location_based_escort.css">
</head>
<body>

<header class="header">
  <h1>ליווי במסלול</h1>
</header>

<main class="content">
  <div class="form-group">
    <label for="destination">כתובת יעד:</label>
    <input type="text" id="destination" placeholder="כתבי כתובת יעד..." autocomplete="off">
    <button id="setDestinationBtn">קבעי יעד</button>
    <ul id="suggestions" class="suggestions-list"></ul>
  </div>

  <div id="map"></div>
</main>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-geometryutil"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script src="../js/Location-based-escort.js"></script>

</body>
</html>
