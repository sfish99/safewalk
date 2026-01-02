<?php
session_start();
//Connection to DB
require "db_connect.php";

// Check if the walker is currently logged in
if (isset($_SESSION['walker_id'])) {
    $id = $_SESSION['walker_id'];

    // Before logging out, we update the 'is_online' status to 0 (offline) 
    $update = $conn->prepare("UPDATE walkers SET is_online = 0 WHERE id = ?");
    $update->bind_param("i", $id);
    $update->execute();
}

// Clear all session variables and destroy the session on the server
session_destroy();
// Redirect the user back to the login page after a successful logout
header("Location: login_walker.php");
exit;
