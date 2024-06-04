<?php
// เชื่อมต่อกับฐานข้อมูล
require 'update/establish.php';

// SQL query เพื่อดึงข้อมูลประวัติการเข้าสู่ระบบ
$history_query = "SELECT history.id_history, prefix.prefix, staff.name_first, staff.name_last, history.action, history.date_time
    FROM history 
    LEFT JOIN login ON login.username = history.username
    LEFT JOIN staff ON login.id_staff = staff.id_staff
    LEFT JOIN prefix ON staff.prefix = prefix.id_prefix
    ORDER BY date_time DESC";
$result = $conn->query($history_query);

if ($result->num_rows > 0) {
    // วนลูปแสดงผลข้อมูลประวัติ
    while ($row = $result->fetch_assoc()) {
        $icon = '';
        if ($row['action'] == 'Logout') {
            $icon = '<i class="me-2 text-red" data-feather="log-out"></i>';
        }
        if ($row['action'] == 'Login') {
            $icon = '<i class="me-2 text-green" data-feather="log-in"></i>';
        }
        echo "<tr class='text-black fw-bold row_staff'>";
        echo "<td class='text-center'>{$row['id_history']}</td>";
        echo "<td class='text-center'>{$row['prefix']}</td>";
        echo "<td class='text-center'>{$row['name_first']}</td>";
        echo "<td class='text-center'>{$row['name_last']}</td>";
        echo "<td class='text-center'>{$icon}{$row['action']}</td>";
        echo "<td class='text-center'>{$row['date_time']}</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6' class='text-center'>No login history available.</td></tr>";
}

// ปิดการเชื่อมต่อกับฐานข้อมูล
$conn->close();
?>
