<?php
require 'update/establish.php';

// ดึงข้อมูลเฉพาะ Foreman
$sql = "SELECT staff.*, role.role
        FROM staff
        JOIN role ON staff.id_role = role.id_role
        WHERE role.role = 'Foreman' AND staff.active = 1
        ORDER BY staff.id_staff ASC";

$result_staff = $conn->query($sql);

if ($result_staff->num_rows > 0) {
    while ($data_staff = $result_staff->fetch_assoc()) {
        echo "<tr class='text-black fw-bold row_staff' data-role='{$data_staff['role']}'>";
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

        // บทบาท
        echo "<td class='role text-center'>" . $data_staff['role'] . "</td>";

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
    echo "<tr><td colspan='7' class='text-center'>ไม่พบข้อมูลพนักงาน</td></tr>";
}
?>