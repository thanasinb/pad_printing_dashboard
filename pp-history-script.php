<?php
require 'update/establish.php';

// รับค่าการกรอง Action จากฟอร์ม
$filterAction = htmlspecialchars($_GET['filter_action'] ?? '', ENT_QUOTES, 'UTF-8');

// สร้าง SQL Query
$history_query = "
    SELECT 
        history.id_history, 
        COALESCE(prefix.prefix, 'N/A') AS prefix, 
        COALESCE(staff.name_first, 'Unknown') AS name_first, 
        COALESCE(staff.name_last, 'Unknown') AS name_last, 
        history.action, 
        history.date_time
    FROM history
    LEFT JOIN login ON login.username = history.username
    LEFT JOIN staff ON login.id_staff = staff.id_staff
    LEFT JOIN prefix ON staff.prefix = prefix.id_prefix
";

if (!empty($filterAction)) {
    $history_query .= " WHERE history.action LIKE ?";
}
$history_query .= " ORDER BY history.date_time DESC";

$stmt = $conn->prepare($history_query);
if (!empty($filterAction)) {
    $filterActionWildcard = '%' . $filterAction . '%';
    $stmt->bind_param("s", $filterActionWildcard);
}

$stmt->execute();
$result = $stmt->get_result();

// ฟังก์ชันสำหรับกำหนด Icon
function getActionIcon($action) {
    if ($action == 'Logout (Log out yourself)') {
        return '<i class="me-2 text-red" data-feather="log-out"></i>';
    } elseif ($action == 'Logout (session expired)') {
        return '<i class="me-2 text-gray" data-feather="log-out"></i>';
    } elseif ($action == 'Login') {
        return '<i class="me-2 text-green" data-feather="log-in"></i>';
    } elseif (strpos($action, 'Download QR Code:') !== false) {
        return '<i class="me-2 text-blue" data-feather="download"></i>';
    } elseif (strpos($action, 'แก้ไขข้อมูลของ:') !== false) {
        return '<i class="me-2 text-blue" data-feather="edit"></i>';
    } elseif (strpos($action, 'บันทึก QR Code:') !== false) {
        return '<i class="me-2 text-blue" data-feather="save"></i>';
    } elseif (strpos($action, 'แก้ไข Description Tha:') !== false) {
        return '<i class="me-2 text-blue" data-feather="edit"></i>';
    } elseif (strpos($action, 'แก้ไข Downtime Code:') !== false) {
        return '<i class="me-2 text-blue" data-feather="edit"></i>';
    } elseif (strpos($action, 'เพิ่ม Downtime:') !== false) {
        return '<i class="me-2 text-green" data-feather="plus-circle"></i>';
    } elseif (strpos($action, 'ลบ downtime:') !== false) {
        return '<i class="me-2 text-red" data-feather="minus-circle"></i>';
    } elseif (strpos($action, 'ลบผู้ใช้:') !== false && strpos($action, 'ของ') !== false) {
        return '<i class="me-2 text-red" data-feather="minus-circle"></i>';
    } elseif (strpos($action, 'เพิ่มผู้ใช้:') !== false && strpos($action, 'ของ') !== false) {
        return '<i class="me-2 text-green" data-feather="plus-circle"></i>';
    }
    return '';
}

// แสดงผลข้อมูล
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $icon = getActionIcon($row['action']); // เรียกใช้ฟังก์ชัน

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
    echo "<tr><td colspan='6' class='text-center'>ไม่มีข้อมูล</td></tr>";
}

$stmt->close();
$conn->close();
?>