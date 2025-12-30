<?php
// Start the session to access logged-in user data
session_start();
// Connect to the database
require "db_connect.php";

// Get user type from the form (volunteer / walker)
$user_type = $_POST['user_type']; // volunteer/walker

// Determine the database table based on user type
$table = $user_type === "volunteer" ? "volunteers" : "walkers";
// ID column name 
$id_col = $user_type === "volunteer" ? "id" : "id";
// Get the logged-in user's ID from the session
$id = $user_type === "volunteer" ? $_SESSION['volunteer_id'] : $_SESSION['walker_id'];

// Get basic profile fields from POST request
$first = $_POST['first_name'];
$last = $_POST['last_name'];
$email = $_POST['email'];
$phone = $_POST['phone'];

// Update basic profile information
$stmt = $conn->prepare("UPDATE $table SET first_name=?, last_name=?, email=?, phone=? WHERE $id_col=?");
$stmt->bind_param("ssssi", $first, $last, $email, $phone, $id);
$stmt->execute();

// Handle profile image upload
if (!empty($_FILES['profile_image']['name'])) {

    $target_dir = "../uploads/profile_images/";
    $ext = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION); // Get uploaded file extension
    $new_filename = $user_type . "_" . $id . "." . $ext; // Create a unique filename based on user type and ID
    $target_file = $target_dir . $new_filename;

    move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file);// Move uploaded file to target directory

    $stmt2 = $conn->prepare("UPDATE $table SET profile_image=? WHERE $id_col=?");
    $stmt2->bind_param("si", $new_filename, $id);
    $stmt2->execute();
}

header("Location: profile_{$user_type}.php");
exit;
