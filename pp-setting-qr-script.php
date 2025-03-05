<?php
require 'update/establish.php';

// คำสั่ง SQL เพื่อดึงข้อมูลจากตาราง qrcodes โดยเรียงลำดับจากเวลาล่าสุด
$sql = "SELECT *, staff.name_first, staff.name_last, role.role
        FROM qrcodes
        LEFT JOIN staff ON qrcodes.id_staff = staff.id_staff
        LEFT JOIN role ON staff.id_role = role.id_role
        ORDER BY qrcodes.gen_date DESC"; /*----ทำให้แสดงข้อมูลจากมากไปน้อย--*/

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // วนลูปเพื่อแสดงข้อมูลในตาราง
    while ($row = $result->fetch_assoc()) {
        echo "<tr class='text-black fw-bold row_staff'>";
        echo '<td class="text-center">' . $row['id_tray'] . '</td>';
        echo '<td class="text-center">
        <img src="' . $row['qr_code_image_path'] . '" 
             alt="QR Code" 
             width="50" height="50" 
             class="qr-thumbnail" 
             onclick="showQrPopup(\'' . $row['qr_code_image_path'] . '\', \'' . $row['id_qr'] . '\', event)">
        <br>' . $row['id_qr'] . '
      </td>';

        echo '<td class="text-center">' . $row['gen_date'] . '</td>';
        echo '<td class="text-center">' . $row['id_staff'] . '</td>';
        echo '<td class="text-center">' . $row['name_first'] . '</td>';
        echo '<td class="text-center">' . $row['name_last'] . '</td>';
        echo '<td class="text-center">' . $row['role'] . '</td>';
        echo '<td class="text-center">
        <button type="button" 
                class="btn btn-datatable btn-icon text-black me-2 delete_qr" 
                data-id_qr="' . htmlspecialchars($row['id_qr']) . '" 
                data-bs-toggle="modal" 
                data-bs-target="#delete_qr_modal">
            <i class="fas fa-trash"></i>
        </button>
      </td>';

        echo '</tr>';

    }
} else {
    echo '<tr><td colspan="7" class="text-center">No data found</td></tr>';
}

// ปิดการเชื่อมต่อ MySQL
$conn->close();
?>

