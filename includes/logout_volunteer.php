<?php
session_start();
require "db_connect.php";

// אם אין סשן — לא עושים כלום
if (isset($_SESSION['volunteer_id'])) {

    $volunteerId = $_SESSION['volunteer_id'];

    // עדכון המתנדבת כלא פעילה
    $stmt = $conn->prepare("UPDATE volunteers SET is_online = 0 WHERE id = ?");
    $stmt->bind_param("i", $volunteerId);
    $stmt->execute();
}

// מחיקת הסשן
session_unset();
session_destroy();

// חזרה לעמוד ההתחברות
header("Location: ../index.html");
exit;
?>
