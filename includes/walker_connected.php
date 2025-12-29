<?php
session_start();

// Check if walker connected
if (!isset($_SESSION['walker_id'])) {
    header("Location: login_walker.php");
    exit;
}
?>