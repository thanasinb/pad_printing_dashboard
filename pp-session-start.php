<?php
session_start();
require 'update/establish.php';

ini_set('session.gc_maxlifetime', 3600);

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 3600)) {
    $logout_user = $_SESSION['username'];

    $stmt = $conn->prepare("INSERT INTO history (username, action, date_time) VALUES (?, 'Logout', NOW())");
    $stmt->bind_param("s", $logout_user);

    if ($stmt->execute()) {
        session_unset();
        session_destroy();

        if (isset($_COOKIE['session_token'])) {
            setcookie("session_token", "", time() - 3600, "/");
        }

        echo "<script>alert('Session หมดอายุแล้ว');</script>";
        echo "<script>window.location.href = 'pp-logout-session.php';</script>";
        exit();
    } else {
        error_log("Failed to insert logout history: " . $stmt->error);
    }

    $stmt->close();
}

$_SESSION['last_activity'] = time();

if (!isset($_SESSION['username'])) {
    if (isset($_COOKIE['session_token'])) {
        $session_token = $_COOKIE['session_token'];

        // Validate the session token
        if (hash_equals($_SESSION['session_token'], $session_token)) {
            $_SESSION['last_activity'] = time();
        } else {
            echo "<script>alert('Session หมดอายุแล้ว');</script>";
            echo "<script>window.location.href = 'pp-logout-session.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Session หมดอายุแล้วจ้า');</script>";
        echo "<script>window.location.href = 'pp-logout-session.php';</script>";
        exit();
    }
}
?>
