<?php
ini_set('display_errors', 0);
error_reporting(E_ERROR | E_WARNING | E_PARSE);
require 'update/establish.php';

// ดึงเฉพาะพนักงานที่มีบทบาทเป็น Technician หรือ Senior Technician
$sql = "SELECT *
        FROM staff
        WHERE id_role IN (2, 8)
        AND active = 1
        ORDER BY id_role ASC, id_staff ASC";

$result_staff = $conn->query($sql);

if ($result_staff->num_rows > 0) {
    while($data_staff = $result_staff->fetch_assoc()) {
        // กำหนดค่า role สำหรับ data-role attribute
        $role_attribute = ($data_staff['id_role'] == 2) ? "Technician" : "Senior Technician";

        echo "<tr class='text-black fw-bold row_staff' data-role='$role_attribute'>";
        echo "<td class='id_staff text-center'>" . $data_staff['id_staff'] . "</td>";
        echo "<td class='rfid text-center'>" . $data_staff['id_rfid'] . "</td>";

        // คำนำหน้า
        echo "<td class='prefix text-center'>";
        $prefix = intval($data_staff['prefix']);
        if ($prefix == 1) echo "นาย";
        elseif ($prefix == 2) echo "นาง";
        elseif ($prefix == 3) echo "นางสาว";
        echo "</td>";

        // ชื่อพนักงาน
        echo "<td class='text-center'>" . $data_staff['name_first'] . " " . $data_staff['name_last'] . "</td>";

        // แสดงบทบาทตาม id_role
        echo "<td class='role text-center'>";
        if ($data_staff['id_role'] == 2) {
            echo "Technician";
        } elseif ($data_staff['id_role'] == 8) {
            echo "Senior Technician";
        }
        echo "</td>";

        // กะการทำงาน
        echo "<td class='shif text-center'>" . $data_staff['id_shif'] . "</td>";
        echo "<td class='text-center'>";
        if (!empty($data_staff['staff_img'])) {
            echo "<img src='uploads/profile/" . htmlspecialchars($data_staff['staff_img']) . "' 
            class='rounded-circle' style='width: 50px; height: 50px; object-fit: cover;'>";
        } else {
            echo "<img src='assets/img/illustrations/profiles/profile-1.png' 
            class='rounded-circle' style='width: 50px; height: 50px; object-fit: cover;'>";
        }
        echo "</td>";

        // ปุ่มแก้ไขและลบ
        echo '<td class="text-center">
                <button type="button"
                        class="btn btn-datatable btn-icon text-black me-2 staff_edit"
                        data-id_staff="' . $data_staff['id_staff'] . '"
                        data-bs-toggle="modal"
                        data-bs-target="#staff_modal">
                    <i class="far fa-edit fs-6"></i>
                </button>
                <button type="button"
                        class="btn btn-datatable btn-icon text-black me-2 staff_delete"
                        data-id_staff="' . $data_staff['id_staff'] . '"
                        data-bs-toggle="modal"
                        data-bs-target="#delete_user_modal">
                    <i class="fas fa-trash"></i>
                </button>
              </td>';
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='7' class='text-center'>ไม่พบข้อมูลพนักงาน Technician หรือ Senior Technician</td></tr>";
}

require 'update/terminate.php';
?>