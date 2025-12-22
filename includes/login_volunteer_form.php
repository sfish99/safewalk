<?php
session_start();
require "db_connect.php"; // file that contain the connection to data base

$email = $_POST['email'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT id, first_name, password_hash, is_approved FROM volunteers WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    $_SESSION['error'] = "האימייל לא נמצא במערכת";
    header("Location: login_volunteer.php");
    exit;

}

$stmt->bind_result($id, $name, $hashedPassword, $isApproved);
$stmt->fetch();

if ($isApproved == 0) {
    $_SESSION['error'] = "החשבון ממתין לאישור מנהלת";
    header("Location: login_volunteer.php");
    exit;
}

if (!password_verify($password, $hashedPassword)) {
    $_SESSION['error'] = "סיסמה שגויה";
    header("Location: login_volunteer.php");
    exit;
}

//create session
$_SESSION['volunteer_id'] = $id;
$_SESSION['volunteer_name'] = $name;

// Update volunteer as online
$update = $conn->prepare("UPDATE volunteers SET is_online = 1 WHERE id = ?");
$update->bind_param("i", $id);
$update->execute();


//Refer to home page
header("Location: home_volunteer.php");
exit;
?>
