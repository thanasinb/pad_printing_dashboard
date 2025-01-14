<?php
session_start();
require 'update/establish.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'], $_POST['message_id'])) {
    $userId = $_SESSION['user_id'];
    $messageId = $_POST['message_id'];

    $sql = "UPDATE messages SET is_read = TRUE WHERE id = ? AND receiver_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $messageId, $userId);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to mark as read']);
    }
    exit();
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
}
?>