<?php
require 'update/establish.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_task'], $_POST['qty_per_pulse2'])) {
    $id_task = trim($_POST['id_task']);
    $qty_per_pulse2 = trim($_POST['qty_per_pulse2']);

    if (!is_numeric($qty_per_pulse2) || $qty_per_pulse2 <= 0) {
        echo "Invalid Qty/Tray value!";
        exit;
    }

    $stmt = $conn->prepare("UPDATE planning SET qty_per_pulse2 = ? WHERE id_task = ?");
    $stmt->bind_param("ds", $qty_per_pulse2, $id_task);

    if ($stmt->execute()) {
        echo "Qty/Tray updated successfully!";
    } else {
        echo "Error updating Qty/Tray.";
    }

    $stmt->close();
}

$conn->close();
?>
