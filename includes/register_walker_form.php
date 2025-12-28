<?php
session_start();

//Connection to DB
require "db_connect.php";

//Get Data
$first = $_POST['first_name'];
$last  = $_POST['last_name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$password = $_POST['password'];

$hashed_password = password_hash($password, PASSWORD_DEFAULT);


//get image from user
$idCardPath = NULL; // Default - image not uploaded

// Check if an image file was uploaded without errors before processing it . code 0 mean no errors
if (isset($_FILES['id_card']) && $_FILES['id_card']['error'] === 0) { // isset() checks if variable exits and his value is not null.
    $imageName = $_FILES['id_card']['name']; // Get the original image name
    $imageTmp = $_FILES['id_card']['tmp_name']; // Get the temporary folder path
    $target_dir = "../id_uploads/walkers_id/"; // Where the image wiil be save
    $target_file = $target_dir . time() . "_" . basename($imageName);// Create a new path and add a time stamp

    
    if (move_uploaded_file($imageTmp, $target_file)) { //Move the image from the temporary folder to the destination folder
        $idCardPath = $target_file; // Now we have the image path
    }
}


// בדיקה אם האימייל כבר קיים
$check = $conn->prepare("SELECT id FROM walkers WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    header("Location: register_error.php");
    exit();
}

// הוספת המשתמש לטבלה
$stmt = $conn->prepare("
    INSERT INTO walkers (first_name, last_name, email, phone, password_hash, id_card_image)
    VALUES (?, ?, ?, ?, ?, ?)
");

$stmt->bind_param("ssssss", 
    $first,
    $last,
    $email,
    $phone,
    $hashed_password,
    $idCardPath
);

if ($stmt->execute()) {
    header("Location: register_success.php");
    exit();
} else {
    header("Location: register_error.php");
    exit();
}

?>
