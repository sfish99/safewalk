<?php
session_start();
require "db_connect.php";
require_once '../../config.php';


header('Content-Type: application/json');

// בדיקה שסשן הולכת רגל קיים
if (!isset($_SESSION['walker_id'])) {
    echo json_encode(['success' => false, 'error' => 'not_authenticated']);
    exit;
}

$walkerId = $_SESSION['walker_id'];

// קריאת JSON
$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

if (!$data) {
    echo json_encode(['success' => false, 'error' => 'invalid_json']);
    exit;
}


$lat = isset($data['latitude']) ? (float)$data['latitude'] : null;
$lng = isset($data['longitude']) ? (float)$data['longitude'] : null;

if ($lat === null || $lng === null) {
    echo json_encode(['success' => false, 'error' => 'missing_coordinates']);
exit;

// אימות: שה־walk_id שייך להולכת הרגל הזאת (מניעת זיוף)
$stmt = $conn->prepare("SELECT walker_id FROM walk_requests WHERE id = ?");
$stmt->bind_param("i", $walker_id);
$stmt->execute();
$res = $stmt->get_result();
if ($row = $res->fetch_assoc()) {
    if ((int)$row['walker_id'] !== (int)$walkerId) {
        echo json_encode(['success' => false, 'error' => 'walk_mismatch']);
        exit;
    }
} else {
    echo json_encode(['success' => false, 'error' => 'walk_not_found']);
    exit;
}

// הוספה לטבלת walk_locations
$stmt = $conn->prepare("INSERT INTO walk_locations (walker_id, latitude, longitude) VALUES (?, ?, ?)");
$stmt->bind_param("idd", $walker_id, $lat, $lng);
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    error_log("DB insert error: " . $stmt->error . "\n");
    echo json_encode(['success' => false, 'error' => 'db_error']);
}
