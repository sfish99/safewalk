<?php
session_start();
//Connection to DB
require "db_connect.php";

// Check if the walker is currently logged in
if (isset($_SESSION['volunteer_id'])) {
    $volunteerId = $_SESSION['volunteer_id'];

    // Before logging out, we update the 'is_online' status to 0 (offline)
    $stmt = $conn->prepare("UPDATE volunteers SET is_online = 0 WHERE id = ?");
    $stmt->bind_param("i", $volunteerId);
    $stmt->execute();
}

// Clear all session variables and destroy the session on the server
session_destroy();

// Redirect the user back to the login page after a successful logout
header("Location: ../index.html");
exit;
?>
