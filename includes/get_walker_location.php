<?php
session_start();
require "db_connect.php";
require_once __DIR__ . '/../../config.php'; // תתאם את הנתיב

header('Content-Type: application/json');

$walk_id = isset($_GET['walk_id']) ? (int)$_GET['walk_id'] : 0;
if (!$walk_id) {
    echo json_encode(['success' => false, 'error' => 'missing_walk_id']);
    exit;
}

// מחזיר את העדכון האחרון מהטבלה walk_locations
$stmt = $conn->prepare("SELECT latitude, longitude, created_at
FROM walk_locations
ORDER BY created_at DESC
LIMIT 1
");
$stmt->bind_param("i", $walk_id);
$stmt->execute();
$res = $stmt->get_result();

if ($row = $res->fetch_assoc()) {
    echo json_encode([
        'success' => true,
        'latitude' => $row['latitude'],
        'longitude' => $row['longitude'],
        'updated_at' => $row['created_at']
    ]);
} else {
    echo json_encode(['success' => false, 'error' => 'no_location']);
}
