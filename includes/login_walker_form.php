<?php
session_start();
require "db_connect.php"; // file that contain the connection to data base

$email = $_POST['email'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT id, first_name, password_hash FROM walkers WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    $_SESSION['error'] = "האימייל לא נמצא במערכת";
    header("Location: login_walker.php");
    exit;
}

$stmt->bind_result($id, $name, $hashedPassword);
$stmt->fetch();

if (!password_verify($password, $hashedPassword)) {
     $_SESSION['error'] = "סיסמה שגויה";
    header("Location: login_walker.php");
    exit;
}

$_SESSION['walker_id'] = $id;
$_SESSION['walker_name'] = $name;

header("Location: Home_walker.php");
exit;
?>
