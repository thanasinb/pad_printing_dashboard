<?php
require 'update/establish.php';

// คำสั่ง SQL เพื่อดึงข้อมูลจากตาราง qrcodes
$sql = "SELECT *, staff.name_first, staff.name_last, role.role
                                FROM qrcodes
                                LEFT JOIN staff ON qrcodes.id_staff = staff.id_staff
                                Left JOIN role ON staff.id_role = role.id_role";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // วนลูปเพื่อแสดงข้อมูลในตาราง
    while ($row = $result->fetch_assoc()) {

        echo "<tr class='text-black fw-bold row_staff'>";
        echo '<td class="text-center">' . $row['id_tray'] . '</td>';
        echo '<td class="text-center">' . $row['id_qr'] . '</td>';
//                                    echo '<td><img src="' . $row['qr_code_image_path'] . '" width="100" height="100"></td>';
        echo '<td class="text-center">' . $row['gen_date'] . '</td>';
        echo '<td class="text-center">' . $row['id_staff'] . '</td>';
        echo '<td class="text-center">' . $row['name_first'] . '</td>';
        echo '<td class="text-center">' . $row['name_last'] . '</td>';
        echo '<td class="text-center">' . $row['role'] . '</td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="3">No data found</td></tr>';
}

// ปิดการเชื่อมต่อ MySQL
$conn->close();
?>