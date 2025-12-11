<?php
session_start();
require "../includes/db_connect.php";

$user_type = $_POST['user_type']; // volunteer/walker

$table = $user_type === "volunteer" ? "volunteers" : "walkers";
$id_col = $user_type === "volunteer" ? "id" : "id";
$id = $user_type === "volunteer" ? $_SESSION['volunteer_id'] : $_SESSION['walker_id'];

// קבלת הערכים
$first = $_POST['first_name'];
$last = $_POST['last_name'];
$email = $_POST['email'];
$phone = $_POST['phone'];

// עדכון בסיסי
$stmt = $conn->prepare("UPDATE $table SET first_name=?, last_name=?, email=?, phone=? WHERE $id_col=?");
$stmt->bind_param("ssssi", $first, $last, $email, $phone, $id);
$stmt->execute();

// טיפול בתמונה
if (!empty($_FILES['profile_image']['name'])) {

    $target_dir = "../uploads/profile_images/";
    $ext = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
    $new_filename = $user_type . "_" . $id . "." . $ext;
    $target_file = $target_dir . $new_filename;

    move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file);

    $stmt2 = $conn->prepare("UPDATE $table SET profile_image=? WHERE $id_col=?");
    $stmt2->bind_param("si", $new_filename, $id);
    $stmt2->execute();
}

header("Location: profile_{$user_type}.php");
exit;
