<?php
require 'update/establish.php'; // เชื่อมต่อฐานข้อมูล

if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error);
    echo json_encode(['error' => 'Database connection failed']);
    exit();
}

// ดึงข้อมูลทั้งหมดก่อนมีการเลือกช่วงเวลา
$query = "SELECT a.id_machine, SUM(p.qty_comp) as total_qty 
          FROM activity a 
          JOIN planning p ON a.id_task = p.id_task 
          GROUP BY a.id_machine";

$stmt = $conn->prepare($query);

if (!$stmt) {
    error_log("Statement preparation failed: " . $conn->error);
    echo json_encode(['error' => 'Statement preparation failed']);
    exit();
}

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
$totalQuantities = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $machines[] = $row['id_machine'];
        $totalQuantities[] = $row['total_qty'];
    }
}

// ส่งข้อมูลกลับในรูปแบบ JSON
echo json_encode([
    'machines' => $machines,
    'totalQuantities' => $totalQuantities
]);

$stmt->close();
$conn->close();
?>