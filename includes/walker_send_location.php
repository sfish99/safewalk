<?php
session_start();
require "db_connect.php";

header('Content-Type: application/json');

// בדיקת סשן הולכת רגל
if (!isset($_SESSION['walker_id'])) {
    echo json_encode(['success' => false, 'error' => 'not_authenticated']);
    exit;
}

$walkerId = (int)$_SESSION['walker_id'];

// קריאת JSON
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['success' => false, 'error' => 'invalid_json']);
    exit;
}

$lat = isset($data['latitude']) ? (float)$data['latitude'] : null;
$lng = isset($data['longitude']) ? (float)$data['longitude'] : null;

if ($lat === null || $lng === null) {
    echo json_encode(['success' => false, 'error' => 'missing_coordinates']);
    exit;
}

// שמירת מיקום
$stmt = $conn->prepare("
    INSERT INTO walk_locations (walker_id, latitude, longitude)
    VALUES (?, ?, ?)
");
$stmt->bind_param("idd", $walkerId, $lat, $lng);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    error_log("DB insert error: " . $stmt->error);
    echo json_encode(['success' => false, 'error' => 'db_error']);
}
