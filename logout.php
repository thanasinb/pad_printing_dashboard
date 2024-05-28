<?php
session_start();

// Include the database connection file
require 'update/establish.php';

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // Record the logout action in the history table
    $logout_time = date('Y-m-d H:i:s');
    $action = "logout";

    $stmt = $conn->prepare("INSERT INTO history (username, action, date_time) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $action, $logout_time);
    $stmt->execute();
    $stmt->close();
}

// Unset and destroy the session
session_unset();
session_destroy();

// Redirect to the homepage or login page
header("Location: pp-homepage.php");
exit();
?>
