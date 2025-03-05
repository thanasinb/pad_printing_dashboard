<?php
require 'update/establish.php';
require 'pp-session-start.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_job = $_POST['id_job'];
    $id_machine = $_POST['id_machine'];
    $username = $_SESSION['username'];

    // เพิ่มงานลงในเครื่องจักร
    $insert_sql = "INSERT INTO machine_queue (id_machine, id_job, queue_number) VALUES (?, ?, 1)";
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("ss", $id_machine, $id_job);

    if ($stmt->execute()) {
        // บันทึกลงใน history
        $log_sql = "INSERT INTO history (username, action, date_time) VALUES (?, ?, NOW())";
        $action = "Add job ID $id_job to machine $id_machine";
        $log_stmt = $conn->prepare($log_sql);
        $log_stmt->bind_param("ss", $username, $action);
        $log_stmt->execute();
        $log_stmt->close();

        echo json_encode(["statusCode" => 200, "message" => "Job added successfully and logged in history."]);
    } else {
        echo json_encode(["statusCode" => 500, "message" => "Failed to add job to machine: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
?>