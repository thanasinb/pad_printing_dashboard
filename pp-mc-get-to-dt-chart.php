<?php
require 'update/establish.php'; // ตรวจสอบว่าเรียกไฟล์ `establish.php` อย่างถูกต้องหรือไม่

// ดึงข้อมูลจากตาราง activity
$query = "SELECT id_machine, COUNT(DISTINCT id_task) as job_count, 
            GROUP_CONCAT(DISTINCT id_task) as tasks 
        FROM activity GROUP BY id_machine";
$result = $conn->query($query);

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

// ดึงข้อมูลจากตาราง activity_downtime
$query_downtime = "SELECT id_machine, SUM(time_total) as downtime_duration 
                   FROM activity_downtime GROUP BY id_machine";
$result_downtime = $conn->query($query_downtime);

$downtimeDurations = [];
if ($result_downtime->num_rows > 0) {
    while ($row = $result_downtime->fetch_assoc()) {
        $downtimeDurations[] = $row['downtime_duration'];
    }
}

$machines_json = json_encode($machines);
$jobCounts_json = json_encode($jobCounts);
$taskDetails_json = json_encode($taskDetails);
$downtimeDurations_json = json_encode($downtimeDurations);

echo "<script>";
echo "const machines = " . $machines_json . ";";
echo "const jobCounts = " . $jobCounts_json . ";";
echo "const taskDetails = " . $taskDetails_json . ";";
echo "const downtimeDurations = " . $downtimeDurations_json . ";";
echo "</script>";

$conn->close();
?>
