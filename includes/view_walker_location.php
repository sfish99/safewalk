<?php
session_start();
require "db_connect.php";
require_once '../../config.php';

// הגנה – רק מתנדבת
if (!isset($_SESSION['volunteer_id'])) {
    header("Location: login_volunteer.php");
    exit;
}

// בדיקה שה־walker_id קיים ב־GET
$walkerId = isset($_GET['walker_id']) ? (int)$_GET['walker_id'] : 0;
if ($walkerId <= 0) die("זהות הולכת הרגל לא תקינה.");

// שליפת שם ותמונה
$stmt = $conn->prepare("SELECT first_name, last_name, profile_image FROM walkers WHERE id = ?");
$stmt->bind_param("i", $walkerId);
$stmt->execute();
$res = $stmt->get_result();
$walker = $res->fetch_assoc();
if (!$walker) die("הולכת רגל לא נמצאה.");

// שליפת המיקום האחרון
$stmt = $conn->prepare("
    SELECT latitude, longitude 
    FROM walk_locations 
    WHERE walker_id = ? 
    ORDER BY created_at DESC 
    LIMIT 1
");
$stmt->bind_param("i", $walkerId);
$stmt->execute();
$res = $stmt->get_result();
$location = $res->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
<meta charset="UTF-8">
<title>מיקום הולכת רגל - SafeWalk</title>
<link rel="stylesheet" href="../css/view_walker_location.css">
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_MAPS_API_KEY; ?>" async defer></script>
</head>
<body>
<header class="header">
    <img src="../images/logo.png" class="logo" alt="logo">
    <h1>מיקום: <?= htmlspecialchars($walker['first_name'] . ' ' . $walker['last_name']); ?></h1>
</header>

<div class="container">
    <?php if (!$location): ?>
        <p>אין מיקום זמין כרגע להולכת הרגל הזאת.</p>
    <?php else: ?>
        <div id="map" style="width:100%; height:400px;"></div>
    <?php endif; ?>
    <a href="online_walkers.php" class="back">חזרה לרשימה</a>
</div>

<?php if ($location): ?>
<script src="../js/view_walker_location.js"></script>
<script>
const WALKER_ID = <?= $walkerId; ?>;
const INITIAL_LAT = <?= $location['latitude']; ?>;
const INITIAL_LNG = <?= $location['longitude']; ?>;
const WALKER_NAME = "<?= htmlspecialchars($walker['first_name']); ?>";
</script>
<?php endif; ?>
</body>
</html>
