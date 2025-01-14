<?php
require 'update/establish.php'; // เชื่อมต่อฐานข้อมูล

if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error);
    echo json_encode(['error' => 'การเชื่อมต่อฐานข้อมูลล้มเหลว']);
    exit();
}

// รับพารามิเตอร์จาก URL
$start_date = $_GET['startDate'];
$end_date = $_GET['endDate'];

// ตรวจสอบพารามิเตอร์
if (isset($start_date, $end_date) && !empty($start_date) && !empty($end_date)) {

    // ตรวจสอบว่า start_date <= end_date
    if (strtotime($start_date) > strtotime($end_date)) {
        echo json_encode(['error' => 'ช่วงวันที่ไม่ถูกต้อง: วันที่เริ่มต้นต้องน้อยกว่าหรือเท่ากับวันที่สิ้นสุด']);
        exit();
    }

    // SQL Query: ดึงข้อมูล Downtime ตามช่วงเวลา
    $query_downtime = "SELECT ad.id_machine, cd.id_code_downtime, 
                          SUM(TIME_TO_SEC(ad.total_work)) / 3600 AS downtime_duration
                   FROM activity_downtime ad
                   JOIN code_downtime cd ON ad.id_downtime = cd.id_downtime
                   WHERE ad.date_eff BETWEEN ? AND ? 
                   GROUP BY ad.id_machine, cd.id_code_downtime";

    $stmt_downtime = $conn->prepare($query_downtime);
    if (!$stmt_downtime) {
        error_log("Statement preparation failed: " . $conn->error);
        echo json_encode(['error' => 'การเตรียม Statement ล้มเหลว']);
        exit();
    }

    $stmt_downtime->bind_param("ss", $start_date, $end_date);
    if (!$stmt_downtime->execute()) {
        error_log("Statement execution failed: " . $stmt_downtime->error);
        echo json_encode(['error' => 'การรัน Statement ล้มเหลว: ' . $stmt_downtime->error]);
        exit();
    }

    $result_downtime = $stmt_downtime->get_result();
    $downtimeDurations = [];
    $downtimeDetails = [];
    $machineDowntime = [];

// ตรวจสอบผลลัพธ์
    if ($result_downtime->num_rows > 0) {
        while ($row = $result_downtime->fetch_assoc()) {
            $id_machine = $row['id_machine'];
            $downtime_duration_hours = $row['downtime_duration'];

            if (!isset($machineDowntime[$id_machine])) {
                $machineDowntime[$id_machine] = 0;
            }
            $machineDowntime[$id_machine] += $downtime_duration_hours;

            if (!isset($downtimeDetails[$id_machine])) {
                $downtimeDetails[$id_machine] = [];
            }
            $downtimeDetails[$id_machine][] = [
                'id_code_downtime' => $row['id_code_downtime'],
                'downtime_duration' => round($downtime_duration_hours, 2)
            ];
        }

        foreach ($machineDowntime as $machine => $duration) {
            $downtimeDurations[] = round($duration, 2);
        }
    }

    $totalDowntime = array_sum($downtimeDurations);
    $stmt_downtime->close();

    echo json_encode([
        'totalDowntime' => round($totalDowntime, 2),
        'machineDowntime' => $machineDowntime,
        'downtimeDurations' => $downtimeDurations,
        'downtimeDetails' => $downtimeDetails
    ]);

} else {
    echo json_encode(['error' => 'พารามิเตอร์ไม่ถูกต้อง']);
}

$conn->close();
?>
