<?php
session_start();

//connect to DB
require "db_connect.php";

// Force JSON response format for this endpoint
header('Content-Type: application/json');

// if there is no session - send to log in page
if (!isset($_SESSION['walker_id'])) {
    echo json_encode(['success' => false, 'error' => 'not_authenticated']);
    exit;
}

// Get walker id from session
$walkerId = (int)$_SESSION['walker_id'];

// Read raw POST body and decode JSON into an associative array
$data = json_decode(file_get_contents('php://input'), true);

// Validate JSON payload
if (!$data) {
    echo json_encode(['success' => false, 'error' => 'invalid_json']);
    exit;
}

// Extract latitude and longitude from request body
$lat = isset($data['latitude']) ? (float)$data['latitude'] : null;
$lng = isset($data['longitude']) ? (float)$data['longitude'] : null;

// Validate required coordinates
if ($lat === null || $lng === null) {
    echo json_encode(['success' => false, 'error' => 'missing_coordinates']);
    exit;
}

// ===== Store Location in Database =====
// Prepare SQL statement to prevent SQL injection
$stmt = $conn->prepare("
    INSERT INTO walk_locations (walker_id, latitude, longitude)
    VALUES (?, ?, ?)
");

// Bind parameters:
// i = integer (walker_id)
// d = double (latitude, longitude)
$stmt->bind_param("idd", $walkerId, $lat, $lng);

// Execute insert and return result
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    // Log database error internally (not exposed to the client)
    error_log("DB insert error: " . $stmt->error);
    echo json_encode(['success' => false, 'error' => 'db_error']);
}
