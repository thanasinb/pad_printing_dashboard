<?php

require 'update/establish.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['qr_code_data'])) {
    $qr_code_data = $conn->real_escape_string($_POST['qr_code_data']);
    $timestamp = date('Y-m-d H:i:s');

    $sql = "INSERT INTO qr_data (qr_code_data, timestamp) VALUES ('$qr_code_data', '$timestamp')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
