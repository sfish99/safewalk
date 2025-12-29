<?php
// Database connection settings and initialization
//Configuration Parameters
$host = "localhost";
$dbUsername = "isshaharshi_safewalk_user";
$dbPassword = "180797Ss!";
$dbName = "isshaharshi_safewalk_db";

//Create Connection
$conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("שגיאה בחיבור לבסיס הנתונים: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4"); // Important for Hebrew
?>
