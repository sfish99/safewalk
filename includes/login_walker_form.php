<?php
session_start();

//Connecrt to data base
require "db_connect.php"; 

// Get the email and password submitted via POST
$email = $_POST['email'];
$password = $_POST['password'];

// SQL statement to select the walker's id, first name, and hashed password based on email
$stmt = $conn->prepare("SELECT id, first_name, password_hash FROM walkers WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

// Check if the email exists in the database
if ($stmt->num_rows === 0) {
    $_SESSION['error'] = "האימייל לא נמצא במערכת";
    header("Location: login_walker.php");
    exit;
}

// Bind the result variables to fetch the data
$stmt->bind_result($id, $name, $hashedPassword);
$stmt->fetch();

// Verify the password entered matches the hashed password in the database
if (!password_verify($password, $hashedPassword)) {
     $_SESSION['error'] = "סיסמה שגויה";
    header("Location: login_walker.php");
    exit;
}
//create session
$_SESSION['walker_id'] = $id;
$_SESSION['walker_name'] = $name;

// Mark walker as online
$update = $conn->prepare("UPDATE walkers SET is_online = 1 WHERE id = ?");
$update->bind_param("i", $id);
$update->execute();

// Redirect the walker to the home page after successful login
header("Location: home_walker.php");
exit;
?>
