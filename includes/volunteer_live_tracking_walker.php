<?php
session_start();
require "db_connect.php";

if (!isset($_SESSION['walker_id'])) {
    header("Location: login_walker.php");
    exit;
}

$walkerName = $_SESSION['walker_name'];
$walkId = $_GET['walk_id'] ?? 0;
?>

<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ליווי בזמן אמת</title>
<link rel="stylesheet" href="../css/volunteer_live_tracking.css">
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY"></script>
</head>
<body>
<h2>ליווי בזמן אמת: <?php echo htmlspecialchars($walkerName); ?></h2>
<div id="map" style="width:100%;height:80vh;"></div>

<script>
let map, marker;

function initMap() {
    map = new google.maps.Map(document.getElementById("map"), {
        zoom: 16,
        center: {lat: 32.0853, lng: 34.7818},
    });

    marker = new google.maps.Marker({map: map, title: "מיקום ההולכת רגל"});

    setInterval(fetchLocation, 5000);
}

function fetchLocation() {
    fetch(`../includes/volunteer_get_location.php?walk_id=<?php echo $walkId; ?>`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const pos = {lat: parseFloat(data.latitude), lng: parseFloat(data.longitude)};
                marker.setPosition(pos);
                map.setCenter(pos);
            }
        });
}

window.onload = initMap;
</script>
</body>
</html>
