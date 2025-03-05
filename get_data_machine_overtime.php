<?php
require 'update/establish.php'; // เชื่อมต่อฐานข้อมูล

if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error);
    echo json_encode(['error' => 'Database connection failed']);
    exit();
}

// รับค่าช่วงวันที่จาก GET
$start_date = $_GET['startDate'] ?? null;
$end_date = $_GET['endDate'] ?? null;

// ถ้ายังไม่มี startDate และ endDate ให้ดึงข้อมูลทั้งหมด
if (!$start_date || !$end_date) {
    $query = "SELECT id_mc, 
                     SUM(CASE WHEN status = 'normal' THEN 1 ELSE 0 END) AS normal_count,
                     SUM(CASE WHEN status = 'over_time' THEN 1 ELSE 0 END) AS overtime_count
              FROM work_time_log
              GROUP BY id_mc";
    $stmt = $conn->prepare($query);
} else {
    $query = "SELECT id_mc, 
                     SUM(CASE WHEN status = 'normal' THEN 1 ELSE 0 END) AS normal_count,
                     SUM(CASE WHEN status = 'over_time' THEN 1 ELSE 0 END) AS overtime_count
              FROM work_time_log
              WHERE date_field BETWEEN ? AND ?
              GROUP BY id_mc";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $start_date, $end_date);
}

if (!$stmt) {
    error_log("Statement preparation failed: " . $conn->error);
    echo json_encode(['error' => 'Statement preparation failed']);
    exit();
}

$stmt->execute();
$result = $stmt->get_result();

$machines = [];
$normalCounts = [];
$overtimeCounts = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $machines[] = $row['id_mc'];
        $normalCounts[] = (int) $row['normal_count'];
        $overtimeCounts[] = (int) $row['overtime_count'];
    }
}else {
    error_log("No data found for date range: {$start_date} - {$end_date}");
    echo json_encode(['error' => 'No data found']);
    exit();
}

// ส่งข้อมูล JSON ให้ JavaScript ใช้
echo json_encode([
    'machines' => $machines,
    'normalCounts' => $normalCounts,
    'overtimeCounts' => $overtimeCounts
]);

$stmt->close();
$conn->close();
?>
