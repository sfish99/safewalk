<?php
session_start();
require "db_connect.php";

if (isset($_SESSION['walker_id'])) {
    $id = $_SESSION['walker_id'];

    $update = $conn->prepare("UPDATE walkers SET is_online = 0 WHERE id = ?");
    $update->bind_param("i", $id);
    $update->execute();
}

session_destroy();
header("Location: login_walker.php");
exit;
