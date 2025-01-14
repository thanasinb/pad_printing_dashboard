<?php
require 'update/establish.php'; // เชื่อมต่อฐานข้อมูล

if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error);
    echo json_encode(['error' => 'Database connection failed']);
    exit();
}

// รับพารามิเตอร์จาก URL
$start_date = $_GET['startDate'];
$end_date = $_GET['endDate'];

// ตรวจสอบให้แน่ใจว่าพารามิเตอร์ถูกกำหนดและไม่ว่างเปล่า
if (isset($start_date) && isset($end_date) && !empty($start_date) && !empty($end_date)) {
    // แปลงวันที่จากวัน/เดือน/ปี เป็น ปี-เดือน-วัน
//    $start_date = DateTime::createFromFormat('m/d/Y', $start_date)->format('Y-m-d');
//    $end_date = DateTime::createFromFormat('m/d/Y', $end_date)->format('Y-m-d');

    // ป้องกัน SQL Injection โดยการเตรียมคำสั่ง SQL
    $stmt = $conn->prepare("SELECT id_machine, COUNT(DISTINCT id_task) as job_count, GROUP_CONCAT(DISTINCT id_task) as tasks 
                        FROM activity 
                        WHERE DATE(date_eff) BETWEEN ? AND ? 
                        OR DATE(date_eff) BETWEEN ? AND ? 
                        GROUP BY id_machine");

    if (!$stmt) {
        error_log("Statement preparation failed: " . $conn->error);
        echo json_encode(['error' => 'Statement preparation failed']);
        exit();
    }

    // ปรับแก้ bind_param ให้ถูกต้องสำหรับ 4 พารามิเตอร์
    $stmt->bind_param("ssss", $start_date, $end_date, $start_date, $end_date);

    if (!$stmt->execute()) {
        error_log("Statement execution failed: " . $stmt->error);
        echo json_encode(['error' => 'Statement execution failed: ' . $stmt->error]);
        exit();
    }

    $result = $stmt->get_result();

    if (!$result) {
        error_log("Getting result failed: " . $stmt->error);
        echo json_encode(['error' => 'Getting result failed']);
        exit();
    }

    $machines = [];
    $jobCounts = [];
    $taskDetails = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $machines[] = $row['id_machine'];
            $jobCounts[] = $row['job_count'];
            $taskDetails[] = explode(',', $row['tasks']);
        }
    }

    // ส่งข้อมูลกลับในรูปแบบ JSON
    echo json_encode([
        'machines' => $machines,
        'jobCounts' => $jobCounts,
        'taskDetails' => $taskDetails
    ]);
} else {
    echo json_encode([
        'error' => 'Invalid parameters'
    ]);
}

$stmt->close();
$conn->close();

?>
