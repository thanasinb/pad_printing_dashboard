<?php
session_start();
require 'update/establish.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $sender_id = $_SESSION['id_staff'];
    $receiver_id = $data['receiver_id'];
    $message_text = htmlspecialchars($data['message'], ENT_QUOTES, 'UTF-8');

    if (sendMessage($sender_id, $receiver_id, $message_text)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}
?>