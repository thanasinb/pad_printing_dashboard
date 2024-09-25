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

    // ดึงข้อมูลจากตาราง activity_downtime และ code_downtime
    $query_downtime = "SELECT ad.id_machine, ad.id_downtime, cd.id_code_downtime, 
                              SUM(TIME_TO_SEC(ad.total_work)) / 3600 as downtime_duration 
                       FROM activity_downtime ad
                       JOIN code_downtime cd ON ad.id_downtime = cd.id_downtime
                       WHERE (DATE(ad.time_start) BETWEEN ? AND ? 
                       OR DATE(ad.time_close) BETWEEN ? AND ?) 
                       GROUP BY ad.id_machine, ad.id_downtime, cd.id_code_downtime";
    $stmt_downtime = $conn->prepare($query_downtime);
    if (!$stmt_downtime) {
        error_log("Statement preparation failed: " . $conn->error);
        echo json_encode(['error' => 'Statement preparation failed']);
        exit();
    }

    $stmt_downtime->bind_param("ssss", $start_date, $end_date, $start_date, $end_date);
    if (!$stmt_downtime->execute()) {
        error_log("Statement execution failed: " . $stmt_downtime->error);
        echo json_encode(['error' => 'Statement execution failed: ' . $stmt_downtime->error]);
        exit();
    }

    $result_downtime = $stmt_downtime->get_result();
    if (!$result_downtime) {
        error_log("Getting result failed: " . $stmt_downtime->error);
        echo json_encode(['error' => 'Getting result failed']);
        exit();
    }

    $downtimeDurations = [];
    $downtimeDetails = [];
    $machineDowntime = [];
    if ($result_downtime->num_rows > 0) {
        while ($row = $result_downtime->fetch_assoc()) {
            $id_machine = $row['id_machine'];
            $downtime_duration_hours = $row['downtime_duration']; // แปลงเป็นชั่วโมง

            // รวมเวลาทั้งหมดสำหรับแต่ละเครื่อง
            if (!isset($machineDowntime[$id_machine])) {
                $machineDowntime[$id_machine] = 0;
            }
            $machineDowntime[$id_machine] += $downtime_duration_hours;

            // เก็บรายละเอียดดาวน์ไทม์สำหรับแต่ละเครื่อง
            if (!isset($downtimeDetails[$id_machine])) {
                $downtimeDetails[$id_machine] = [];
            }
            $downtimeDetails[$id_machine][] = [
                'id_code_downtime' => $row['id_code_downtime'],
                'downtime_duration' => $downtime_duration_hours
            ];
        }

        foreach ($machineDowntime as $machine => $duration) {
            $downtimeDurations[] = $duration;
        }
    }
    $stmt_downtime->close();

    // ส่งข้อมูลกลับในรูปแบบ JSON
    echo json_encode([
        'machineDowntime' => $machineDowntime,
        'downtimeDurations' => $downtimeDurations,
        'downtimeDetails' => $downtimeDetails
    ]);
} else {
    echo json_encode([
        'error' => 'Invalid parameters'
    ]);
}

$conn->close();
?>
