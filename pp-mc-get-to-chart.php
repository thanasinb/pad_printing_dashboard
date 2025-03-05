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
$query_downtime = "SELECT ad.id_machine, ad.id_downtime, cd.id_code_downtime, 
                              SUM(TIME_TO_SEC(ad.total_work)) / 3600 as downtime_duration 
                       FROM activity_downtime ad
                       JOIN code_downtime cd ON ad.id_downtime = cd.id_downtime
                       GROUP BY ad.id_machine, ad.id_downtime, cd.id_code_downtime";;
$result_downtime = $conn->query($query_downtime);

$downtimeDurations = [];
$downtimeDetails = [];
if ($result_downtime->num_rows > 0) {
    $machineDowntime = [];
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

    // สร้างข้อมูล downtimeDurations จาก machineDowntime
    foreach ($machines as $machine) {
        $downtimeDurations[] = isset($machineDowntime[$machine]) ? $machineDowntime[$machine] : 0;
    }
}
$query_planning = "SELECT a.id_machine, SUM(p.qty_comp) as total_qty FROM activity a JOIN planning p ON a.id_task = p.id_task GROUP BY a.id_machine";
$result_planning = $conn->query($query_planning);

$totalQuantities = [];
if ($result_planning->num_rows > 0) {
    while ($row = $result_planning->fetch_assoc()) {
        $totalQuantities[$row['id_machine']] = $row['total_qty'];
    }
}

$machines_json = json_encode($machines);
$jobCounts_json = json_encode($jobCounts);
$taskDetails_json = json_encode($taskDetails);
$downtimeDurations_json = json_encode($downtimeDurations);
$downtimeDetails_json = json_encode($downtimeDetails);
$totalQuantities_json = json_encode($totalQuantities);

echo "<script>";
echo "const machines = " . $machines_json . ";";
echo "const jobCounts = " . $jobCounts_json . ";";
echo "const taskDetails = " . $taskDetails_json . ";";
echo "const downtimeDurations = " . $downtimeDurations_json . ";";
echo "const downtimeDetails = " . $downtimeDetails_json . ";";
echo "const totalQuantities = .$totalQuantities_json";
echo "</script>";

$conn->close();
?>
