<?php
require 'update/establish.php';

// รับค่าช่วงวันที่จาก GET
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : null;
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : null;

// สร้างเงื่อนไข WHERE สำหรับช่วงเวลา
$whereClause = "";
$params = [];
$types = "";

if ($start_date && $end_date) {
    $whereClause = "WHERE DATE(q.timestamp) BETWEEN ? AND ?";
    $params = [$start_date, $end_date];
    $types = "ss";
} elseif ($start_date) {
    $whereClause = "WHERE DATE(q.timestamp) >= ?";
    $params = [$start_date];
    $types = "s";
} elseif ($end_date) {
    $whereClause = "WHERE DATE(q.timestamp) <= ?";
    $params = [$end_date];
    $types = "s";
}

// 1) Query ดึงข้อมูลจาก qr_data + activity + planning + staff
$sql = "
    SELECT 
        q.id,
        q.id_cam,
        q.qr_code_data,
        DATE(q.timestamp) AS date,
        MIN(q.timestamp) AS time_in,
        MAX(q.timestamp) AS time_out,
        TIMEDIFF(MAX(q.timestamp), MIN(q.timestamp)) AS total_time,
        m.id_mc,
        a.id_task,
        a.id_staff,
        a.total_work,
        COALESCE(p.qty_per_pulse2, 0) AS planning_qty_per_pulse2,
        p.run_time_std,
        s.name_first,
        s.name_last
    FROM qr_data q
    LEFT JOIN machine m ON q.id_cam = m.id_cam
    LEFT JOIN activity a ON m.id_mc = a.id_machine
    LEFT JOIN planning p ON a.id_task = p.id_task
    LEFT JOIN staff s ON a.id_staff = s.id_staff
    $whereClause
    GROUP BY q.id_cam, q.qr_code_data, DATE(q.timestamp)
    ORDER BY a.id_staff
";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$operatorStats = array();

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $total_time = isset($row['total_time']) ? $row['total_time'] : "00:00:00";
        $timeArr = explode(":", $total_time);
        $total_time_in_min = intval($timeArr[0]) * 60 + intval($timeArr[1]) + (intval($timeArr[2]) / 60.0);

        $std_seconds = isset($row['run_time_std']) ? $row['run_time_std'] * 3600 : null;
        $qty_per_set = $row['planning_qty_per_pulse2'];
        $time_per_set_sec = ($std_seconds !== null) ? $std_seconds * $qty_per_set : null;
        $time_per_set_min = ($time_per_set_sec !== null) ? $time_per_set_sec / 60 : null;

        $isMiss = ($time_per_set_min !== null && $total_time_in_min > $time_per_set_min);

        $id_staff = $row['id_staff'];
        $id_task = $row['id_task'];
        $staff_name = trim($row['name_first']) . ' ' . trim($row['name_last']);
        $stat_date = $row['date'];

        if (!isset($operatorStats[$id_staff])) {
            $operatorStats[$id_staff] = array(
                'id_staff'       => $id_staff,
                'staff_name'     => $staff_name,
                'stat_date'      => $stat_date,
                'total_jobs'     => 0,
                'correct_target' => 0,
                'miss_target'    => 0
            );
        }

        $operatorStats[$id_staff]['total_jobs']++;
        if ($isMiss) {
            $operatorStats[$id_staff]['miss_target']++;
        } else {
            $operatorStats[$id_staff]['correct_target']++;
        }
    }
}

foreach ($operatorStats as $stat) {
    $sql_insert = "
        INSERT INTO operator_task_statistic (id_staff, id_task, stat_date, total_jobs, correct_target, miss_target, created_at, updated_at) 
        VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())
        ON DUPLICATE KEY UPDATE 
        total_jobs = VALUES(total_jobs),
        correct_target = VALUES(correct_target),
        miss_target = VALUES(miss_target),
        updated_at = NOW()
    ";

    $stmt = $conn->prepare($sql_insert);
    $stmt->bind_param("sssiii", $stat['id_staff'], $id_task, $stat['stat_date'], $stat['total_jobs'], $stat['correct_target'], $stat['miss_target']);
    $stmt->execute();
}

$i = 1;
if (!empty($operatorStats)) {
    foreach ($operatorStats as $stat) {
        echo "<tr>";
        echo "<td class='text-center'>" . $i++ . "</td>";
        echo "<td class='text-center'>" . htmlspecialchars($stat['id_staff']) . "</td>";
        echo "<td class='text-center'>" . htmlspecialchars($stat['staff_name']) . "</td>";
        echo "<td class='text-center'>" . htmlspecialchars($stat['stat_date']) . "</td>";
        echo "<td class='text-center'>" . htmlspecialchars($stat['total_jobs']) . "</td>";
        echo "<td class='text-center'>" . htmlspecialchars($stat['correct_target']) . "</td>";
        echo "<td class='text-center'>" . htmlspecialchars($stat['miss_target']) . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='7' class='text-center'>No entries found</td></tr>";
}

$conn->close();
?>