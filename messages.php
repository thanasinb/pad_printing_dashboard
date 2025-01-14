<?php
session_start();
require 'update/establish.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'], $_POST['receiver_id'], $_POST['message'])) {
    $senderId = $_SESSION['user_id'];
    $receiverId = $_POST['receiver_id'];
    $message = htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8');

    $sql = "INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $senderId, $receiverId, $message);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to send message']);
    }
    exit();
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
}
?>