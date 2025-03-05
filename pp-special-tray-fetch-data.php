<?php
require 'update/establish.php';

$sql = "
    SELECT q.id, 
           q.id_cam, 
           q.qr_code_data, 
           DATE(q.timestamp) as date, 
           MIN(q.timestamp) as time_in, 
           MAX(q.timestamp) as time_out, 
           TIMEDIFF(MAX(q.timestamp), MIN(q.timestamp)) as total_time,
           m.id_mc,
           a.id_task,
           a.id_staff,
           a.total_work,
           COALESCE(p.qty_per_pulse2, 0) + COALESCE(s.qty_per_pulse2, 0) as total_qty_per_pulse2,
           p.run_time_std
    FROM qr_data q
    LEFT JOIN machine m ON q.id_cam = m.id_cam
    LEFT JOIN activity a ON m.id_mc = a.id_machine
    LEFT JOIN planning p ON a.id_task = p.id_task
    LEFT JOIN special_tray_data s ON q.qr_code_data = s.tray_id
    GROUP BY q.id_cam, q.qr_code_data, DATE(q.timestamp)
    ORDER BY date DESC, time_in ASC";

$result = $conn->query($sql);
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

$conn->close();
echo json_encode($data);
?>
