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
    $address = $_POST['address'];

    // Geocoding עם Google API
    $apiKey = "AIzaSyD4797KA2grZRJK0NiNgVsLykPATAiHi04";
    $addressUrl = urlencode($address);
    $url = "https://maps.googleapis.com/maps/api/geocode/json?address=$addressUrl&key=$apiKey";

    $resp_json = file_get_contents($url);
    $resp = json_decode($resp_json, true);

    if($resp['status'] == 'OK') {
        $lat = $resp['results'][0]['geometry']['location']['lat'];
        $lng = $resp['results'][0]['geometry']['location']['lng'];

        $stmt = $conn->prepare("INSERT INTO walk_requests (walker_id, destination_lat, destination_lng, status, started_at) VALUES (?, ?, ?, 'active', NOW())");
        $stmt->bind_param("idd", $walkerId, $lat, $lng);
        if ($stmt->execute()) {
            $walkId = $stmt->insert_id;
            $_SESSION['current_walk_id'] = $walkId;
            header("Location: walker_walk_map.php");
            exit;
        } else {
            $error = "שגיאה ביצירת ההליכה";
        }
    } else {
        $error = "כתובת לא חוקית, נסי שוב";
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
    <label>כתובת יעד:</label>
    <input type="text" name="address" required><br>
    <button type="submit">התחל הליכה</button>
</form>
</body>
</html>
