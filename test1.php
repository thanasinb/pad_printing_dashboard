<?php
require 'update/establish.php'; // เชื่อมต่อฐานข้อมูล

if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error);
    echo json_encode(['error' => 'Database connection failed']);
    exit();
}

// ดึงข้อมูลจากตาราง activity
$query_activity = "SELECT id_machine, COUNT(DISTINCT id_task) as job_count, GROUP_CONCAT(DISTINCT id_task) as tasks FROM activity GROUP BY id_machine";
$result_activity = $conn->query($query_activity);

$machines = [];
$jobCounts = [];
$taskDetails = [];
if ($result_activity->num_rows > 0) {
    while ($row = $result_activity->fetch_assoc()) {
        $machines[] = $row['id_machine'];
        $jobCounts[] = $row['job_count'];
        $taskDetails[] = explode(',', $row['tasks']);
    }
}

// ดึงข้อมูลจากตาราง activity_downtime
$query_downtime = "SELECT ad.id_machine, cd.id_code_downtime, SUM(TIME_TO_SEC(ad.total_work)) / 3600 as downtime_duration FROM activity_downtime ad JOIN code_downtime cd ON ad.id_downtime = cd.id_downtime GROUP BY ad.id_machine, cd.id_code_downtime";
$result_downtime = $conn->query($query_downtime);

$downtimeDurations = [];
$downtimeDetails = [];
$machineDowntime = [];
if ($result_downtime->num_rows > 0) {
    while ($row = $result_downtime->fetch_assoc()) {
        $id_machine = $row['id_machine'];
        $downtime_duration_hours = $row['downtime_duration'];

        $machineDowntime[$id_machine] = ($machineDowntime[$id_machine] ?? 0) + $downtime_duration_hours;
        $downtimeDetails[$id_machine][] = [
            'id_code_downtime' => $row['id_code_downtime'],
            'downtime_duration' => $downtime_duration_hours
        ];
    }
    foreach ($machines as $machine) {
        $downtimeDurations[] = $machineDowntime[$machine] ?? 0;
    }
}

// ดึงข้อมูลจาก planning
$query_planning = "SELECT a.id_machine, SUM(p.qty_comp) as total_qty FROM activity a JOIN planning p ON a.id_task = p.id_task GROUP BY a.id_machine";
$result_planning = $conn->query($query_planning);

$totalQuantities = [];
if ($result_planning->num_rows > 0) {
    while ($row = $result_planning->fetch_assoc()) {
        $totalQuantities[$row['id_machine']] = $row['total_qty'];
    }
}

// ส่งข้อมูลทั้งหมดเป็น JSON
echo json_encode([
    'machines' => $machines,
    'jobCounts' => $jobCounts,
    'taskDetails' => $taskDetails,
    'downtimeDurations' => $downtimeDurations,
    'downtimeDetails' => $downtimeDetails,
    'totalQuantities' => $totalQuantities
]);

$conn->close();
?>
