<?php
session_start();
require "db_connect.php";
header('Content-Type: application/json');

if (!isset($_SESSION['walker_id'])) {
    echo json_encode(['success'=>false]);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$walk_id = $data['walk_id'];
$lat = $data['latitude'];
$lng = $data['longitude'];

$stmt = $conn->prepare("INSERT INTO walker_location (walk_id, latitude, longitude) VALUES (?, ?, ?)");
$stmt->bind_param("idd", $walk_id, $lat, $lng);
if ($stmt->execute()) {
    echo json_encode(['success'=>true]);
} else {
    echo json_encode(['success'=>false]);
}
