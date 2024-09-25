<?php
session_start();
require 'update/establish.php'; // Using establish.php for database connection

// Get the JSON data from the request
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['action']) && isset($_SESSION['username'])) {
    $action = $data['action'];
    $username = $_SESSION['username'];
    $datetime = date('Y-m-d H:i:s');

    // Insert the action into the history table
    $query = "INSERT INTO history (username, action, date_time) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $username, $action, $datetime);

    if ($stmt->execute()) {
        // Return success response
        echo json_encode(['status' => 'success']);
    } else {
        // Return error response
        echo json_encode(['status' => 'error', 'message' => 'Database error']);
    }
    $stmt->close();
} else {
    // Return error response
    echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
}

$conn->close();
?>
