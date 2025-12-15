<?php
session_start();
require "db_connect.php";
require_once '../../config.php';

header('Content-Type: application/json');

// בדוק שהמשתמש הוא מתנדבת
if (!isset($_SESSION['volunteer_id'])) {
    echo json_encode(['success' => false, 'error' => 'not_authenticated']);
    exit;
}

// שלוף את כל ההליכות הפעילות
$stmt = $conn->prepare("
    SELECT w.walker_id, w.first_name, wl.latitude, wl.longitude, wl.created_at
    FROM walk_requests w
    JOIN walk_locations wl ON wl.walk_id = w.walker_id
    WHERE w.status = 'active'
    AND wl.id = (
        SELECT MAX(id) FROM walk_locations WHERE walk_id = w.walker_id
    )
");
$stmt->execute();
$res = $stmt->get_result();

$walkers = [];
while ($row = $res->fetch_assoc()) {
    $walkers[] = [
        'walker_id' => (int)$row['walker_id'],
        'first_name' => $row['first_name'],
        'latitude' => (float)$row['latitude'],
        'longitude' => (float)$row['longitude'],
        'updated_at' => $row['created_at']
    ];
}

echo json_encode($walkers);
