<?php
session_start();
require 'update/establish.php';

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    $sql = "SELECT m.*, u.username AS sender_username, u.profile_image AS sender_image 
            FROM messages m
            INNER JOIN users u ON m.sender_id = u.id
            WHERE m.receiver_id = ? AND m.is_read = FALSE
            ORDER BY m.timestamp DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }

    echo json_encode($messages);
    exit();
} else {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
}
?>