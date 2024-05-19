<?php


// เชื่อมต่อกับฐานข้อมูล
require 'update/establish.php';

// SQL query เพื่อดึงข้อมูลประวัติการเข้าสู่ระบบ
$history_query = "SELECT * ,staff.name_first, staff.name_last, prefix.prefix
    FROM history 
    LEFT JOIN login ON login.username = history.username
    left join staff on login.id_staff = staff.id_staff
    left join prefix on staff.prefix = prefix.id_prefix
    ORDER BY date_time DESC";
    $result = $conn->query($history_query);


if ($result->num_rows > 0) {

    // วนลูปแสดงผลข้อมูลประวัติ
    while ($row = $result->fetch_assoc()) {
        echo "<tr class='text-black fw-bold row_staff'>";
        echo'<td class="text-center">' . $row['id_history'] . '</td>';
        echo'<td class="text-center">' . $row['prefix'].'</td>';
        echo'<td class="text-center">' . $row['name_first'].'</td>';
        echo'<td class="text-center">' . $row['name_last'] . '</td>';
        echo'<td class="text-center">' . $row['action'] . '</td>';
        echo'<td class="text-center">' . $row['date_time'] . '</td>';
        echo'</tr>';
    }

    echo '</table>';
} else {
    echo 'No login history available.';
}

// ปิดการเชื่อมต่อกับฐานข้อมูล
$conn->close();
?>