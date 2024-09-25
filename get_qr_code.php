<?php
require 'update/establish.php';
session_start();

if (!isset($_SESSION['username']) || !isset($_POST['id_qr'])) {
    http_response_code(403);
    exit();
}

$id_qr = htmlspecialchars($_POST['id_qr']);

$sql = "SELECT qr_code FROM qrcodes WHERE id_qr = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id_qr);
$stmt->execute();
$result = $stmt->get_result();

$qr_codes = [];
while ($row = $result->fetch_assoc()) {
    $qr_codes[] = $row['qr_code'];
}

$stmt->close();
$conn->close();

echo json_encode($qr_codes);
?>