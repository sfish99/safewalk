<?php
session_start();
require "db_connect.php";

if (!isset($_SESSION['walker_id']) || !isset($_SESSION['current_walk_id'])) {
    header("Location: walker_start_walk.php");
    exit;
}

$walkerId = $_SESSION['walker_id'];
$walkId = $_SESSION['current_walk_id'];
?>

<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>הליכה בזמן אמת</title>
<link rel="stylesheet" href="../css/walker_walk_map.css">
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY"></script>
</head>
<body>

<h2>הליכה בזמן אמת</h2>
<div id="map" style="width:100%;height:80vh;"></div>

<script>
let map, marker;

function initMap() {
    map = new google.maps.Map(document.getElementById("map"), {
        zoom: 16,
        center: {lat: 32.0853, lng: 34.7818},
    });

    marker = new google.maps.Marker({
        map: map,
        title: "מיקום נוכחי",
    });

    if (navigator.geolocation) {
        navigator.geolocation.watchPosition(sendPosition, showError, {enableHighAccuracy:true});
    } else {
        alert("דפדפן זה אינו תומך ב-GPS");
    }
}

function sendPosition(position) {
    const lat = position.coords.latitude;
    const lng = position.coords.longitude;

    marker.setPosition({lat, lng});
    map.setCenter({lat, lng});

    fetch('walker_send_location.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({walk_id: <?php echo $walkId; ?>, latitude: lat, longitude: lng})
    });
}

function showError(error) {
    console.error(error);
}

window.onload = initMap;
</script>

</body>
</html>
