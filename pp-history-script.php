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
        } elseif ($row['action'] == 'Login') {
            $icon = '<i class="me-2 text-green" data-feather="log-in"></i>';
        } elseif (strpos($row['action'], 'Download QR Code:') !== false) {
            $icon = '<i class="me-2 text-blue" data-feather="download"></i>';
        } elseif (strpos($row['action'], 'แก้ไขข้อมูลของ:') !== false) {
            $icon = '<i class="me-2 text-blue" data-feather="edit"></i>';
        } elseif (strpos($row['action'], 'บันทึก QR Code:') !== false) {
            $icon = '<i class="me-2 text-blue" data-feather="save"></i>';
        } elseif (strpos($row['action'], 'แก้ไข Description Tha:') !== false) {
            $icon = '<i class="me-2 text-blue" data-feather="edit"></i>';
        } elseif (strpos($row['action'], 'แก้ไข Downtime Code:') !== false) {
            $icon = '<i class="me-2 text-blue" data-feather="edit"></i>';
        } elseif (strpos($row['action'], 'เพิ่ม Downtime:') !== false) {
            $icon = '<i class="me-2 text-blue" data-feather="plus-circle"></i>';
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
    echo "<tr><td colspan='6' class='text-center'>ไม่มีประวัติการใช้งานที่พร้อมแสดง</td></tr>";
}

// ปิดการเชื่อมต่อกับฐานข้อมูล
$conn->close();
?>
