<?php
require 'update/establish.php'; // เชื่อมต่อฐานข้อมูล

if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error);
    echo json_encode(['error' => 'การเชื่อมต่อฐานข้อมูลล้มเหลว']);
    exit();
}

// รับพารามิเตอร์จาก URL
$start_date = $_GET['startDate'] ?? null;
$end_date = $_GET['endDate'] ?? null;

// ตรวจสอบพารามิเตอร์
if ($start_date && $end_date && !empty($start_date) && !empty($end_date)) {

    // ตรวจสอบว่า start_date <= end_date
    if (strtotime($start_date) > strtotime($end_date)) {
        echo json_encode(['error' => 'โปรดระบุช่วงวันให้ถูกต้อง']);
        exit();
    }

    // SQL Query: ดึงข้อมูล Downtime ตามช่วงเวลา
    $query_downtime = "
        SELECT 
            ad.id_machine, 
            cd.id_code_downtime, 
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
            $downtime_duration_hours = (float) $row['downtime_duration'];

            // สะสม Downtime ของแต่ละเครื่อง
            if (!isset($machineDowntime[$id_machine])) {
                $machineDowntime[$id_machine] = 0;
            }
            $machineDowntime[$id_machine] += $downtime_duration_hours;

            // เก็บรายละเอียด Downtime
            if (!isset($downtimeDetails[$id_machine])) {
                $downtimeDetails[$id_machine] = [];
            }
            $downtimeDetails[$id_machine][] = [
                'id_code_downtime' => $row['id_code_downtime'],
                'downtime_duration' => round($downtime_duration_hours, 2)
            ];
        }

        // เตรียมข้อมูล Downtime Durations
        foreach ($machineDowntime as $machine => $duration) {
            $downtimeDurations[] = round($duration, 2);
        }
    }else {
        error_log("No data found for date range: {$start_date} - {$end_date}");
        echo json_encode(['error' => 'No data found']);
        exit();
    }

    $totalDowntime = array_sum($downtimeDurations);
    $stmt_downtime->close();
    error_log("Start Date: $start_date, End Date: $end_date");

// Debug ก่อนส่งข้อมูลกลับ
    error_log(print_r([
        'totalDowntime' => $totalDowntime,
        'machineDowntime' => $machineDowntime,
        'downtimeDurations' => $downtimeDurations,
        'downtimeDetails' => $downtimeDetails
    ], true));

    // ส่งข้อมูลกลับในรูปแบบ JSON
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
