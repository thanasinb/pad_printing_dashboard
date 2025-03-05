<?php
header('Content-Type: application/json');
require 'update/establish.php';

// 🔹 ปิด Error Output เพื่อไม่ให้ไปรบกวน JSON Output
ini_set('display_errors', 0);
error_reporting(E_ALL);

if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    echo json_encode(['error' => 'Database connection failed']);
    exit();
}

$start_date = $_POST['startDate'] ?? null;
$end_date = $_POST['endDate'] ?? null;

// 🔹 ตรวจสอบว่าค่า Start และ End Date ถูกส่งมาหรือไม่
if (!$start_date || !$end_date) {
    error_log("Invalid date parameters: start_date={$start_date}, end_date={$end_date}");
    echo json_encode(['error' => 'Invalid date parameters']);
    exit();
}

// 🔹 ตรวจสอบว่า Format ของ Date ถูกต้อง
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $start_date) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $end_date)) {
    error_log("Invalid date format: start_date={$start_date}, end_date={$end_date}");
    echo json_encode(['error' => 'Invalid date format']);
    exit();
}
if (strtotime($start_date) > strtotime($end_date)) {
    echo json_encode(['error' => 'โปรดระบุช่วงวันให้ถูกต้อง']);
    exit();
}

// 🔹 SQL Query ตรวจสอบข้อมูล
$sql = "SELECT a.id_machine, COALESCE(SUM(p.qty_comp), 0) as total_qty
        FROM activity a
        JOIN planning p ON a.id_task = p.id_task
        WHERE DATE(p.datetime_update) BETWEEN ? AND ?
        GROUP BY a.id_machine";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    error_log("SQL Preparation Failed: " . $conn->error);
    echo json_encode(['error' => 'SQL Preparation Failed']);
    exit();
}

$stmt->bind_param("ss", $start_date, $end_date);
$stmt->execute();
$result = $stmt->get_result();

$machines = [];
$totalQuantities = [];

// 🔹 ตรวจสอบว่ามีข้อมูลหรือไม่
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $machines[] = $row['id_machine'];
        $totalQuantities[$row['id_machine']] = (int)$row['total_qty'];
    }
} else {
    error_log("No data found for date range: {$start_date} - {$end_date}");
    echo json_encode(['error' => 'No data found']);
    exit();
}

// ✅ ส่งข้อมูลกลับไปให้ JavaScript
echo json_encode([
    'machines' => $machines,
    'totalQuantities' => $totalQuantities
]);

$stmt->close();
$conn->close();
?>
