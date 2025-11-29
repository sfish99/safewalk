<?php
session_start();
require "db_connect.php"; // file that contain the connection to data base

$email = $_POST['email'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT id, first_name, password_hash FROM volunteers WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    die("האימייל לא נמצא במערכת");
}

$stmt->bind_result($id, $name, $hashedPassword);
$stmt->fetch();

if (!password_verify($password, $hashedPassword)) {
    die("סיסמה שגויה");
}

$_SESSION['volunteer_id'] = $id;
$_SESSION['volunteer_name'] = $name;

header("Location: volunteer_dashboard.php");
exit;
?>
