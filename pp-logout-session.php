<?php
session_start();
require 'update/establish.php';

if (isset($_SESSION['username'])) {
    $logout_user = $_SESSION['username'];

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO history (username, action, date_time) VALUES (?, 'Logout', NOW())");
    $stmt->bind_param("s", $logout_user);
    $stmt->execute();
    $stmt->close();
}

// Clear all session variables
session_unset();
session_destroy();

// Delete the username cookie
if (isset($_COOKIE['username'])) {
    setcookie("username", "", time() - 3600, "/");
}

// Redirect to the login page
header("Location: pp-homepage.php");
exit();
?>
