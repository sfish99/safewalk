<?php
session_start();

require "db_connect.php";//connect to DB

require_once '../../config.php';// API key folder

// if there is no session - send to log in page
if (!isset($_SESSION['volunteer_id'])) {
    header("Location: login_volunteer.php");
    exit;
}

// ===== Validate walker_id from URL =====
// Get walker ID from GET parameter and cast to integer
$walkerId = isset($_GET['walker_id']) ? (int)$_GET['walker_id'] : 0;

// Stop execution if walker ID is invalid
if ($walkerId <= 0) die("זהות הולכת הרגל לא תקינה.");

// ===== Fetch Walker Details =====
// Retrieve walker's name and profile image
$stmt = $conn->prepare("SELECT first_name, last_name, profile_image FROM walkers WHERE id = ?");
$stmt->bind_param("i", $walkerId);
$stmt->execute();
$res = $stmt->get_result();
$walker = $res->fetch_assoc();

if (!$walker) die("הולכת רגל לא נמצאה.");// Stop execution if walker does not exist

// ===== Fetch Latest Walker Location =====
// Get the most recent location entry for the walker
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
$volName = $_SESSION['volunteer_name'];
?>


<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>מיקום הולכת רגל - SafeWalk</title>
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/view_walker_location.css">
    <!-- Google Maps JavaScript API -->
    <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_MAPS_API_KEY; ?>" async defer></script>
</head>
<body>

<!--Loading header-->
<div id="header-container">
    <?php include 'header_volunteer.php'; ?>
</div>

<div class="container">
    <h1>מיקום: <?= htmlspecialchars($walker['first_name'] . ' ' . $walker['last_name']); ?></h1>
    <?php if (!$location): ?>
        <p>אין מיקום זמין כרגע להולכת הרגל הזאת.</p>
    <?php else: ?>
        <div id="map" style="width:100%; height:400px;"></div>
    <?php endif; ?>
    <a href="online_walkers.php" class="back">חזרה לרשימה</a>
</div>

<?php if ($location): ?>
<script src="../js/view_walker_location.js"></script>

<!-- Pass PHP data to JavaScript -->
<script>
const WALKER_ID = <?= $walkerId; ?>;
const INITIAL_LAT = <?= $location['latitude']; ?>;
const INITIAL_LNG = <?= $location['longitude']; ?>;
const WALKER_NAME = "<?= htmlspecialchars($walker['first_name']); ?>";
</script>
<?php endif; ?>
</body>
</html>
