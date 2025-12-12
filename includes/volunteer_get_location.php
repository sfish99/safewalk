<?php
session_start();
require "db_connect.php";
header('Content-Type: application/json');

$walk_id = $_GET['walk_id'] ?? 0;

$stmt = $conn->prepare("SELECT latitude, longitude FROM walker_location WHERE walk_id = ? ORDER BY updated_at DESC LIMIT 1");
$stmt->bind_param("i", $walk_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode(['success'=>true,'latitude'=>$row['latitude'],'longitude'=>$row['longitude']]);
} else {
    echo json_encode(['success'=>false]);
}
