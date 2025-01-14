<?php
ini_set('display_errors', 0);
error_reporting(E_ERROR | E_WARNING | E_PARSE);
require 'update/establish.php';
$sql = "SELECT *
        FROM staff
        WHERE id_role IN (
            SELECT id_role
            FROM role
            WHERE role_group IN (2, 3)  -- เลือก role_group เท่ากับ 2 หรือ 3
        )
        AND active = 1
        ORDER BY id_role ASC, id_staff ASC";
$result_staff = $conn->query($sql);

while($data_staff = $result_staff->fetch_assoc()) {
    echo "<tr class='text-black fw-bold row_staff'>";
    echo "<td class='id_staff text-center'>" . $data_staff['id_staff'] . "</td>";
    echo "<td class='rfid text-center'>" . $data_staff['id_rfid'] . "</td>";
    echo "<td class='prefix text-center'>";
    $prefix = intval($data_staff['prefix']);
    if ($prefix == 1)
        echo "นาย";
    elseif ($prefix == 2)
        echo "นาง";
    elseif ($prefix == 3)
        echo "นางสาว";
    echo "</td>";
    echo "<td class='text-center'>" . $data_staff['name_first'] . " " . $data_staff['name_last'] . "</td>";

    echo "<td class='role text-center'>";
    $id_role = intval($data_staff['id_role']);
    if ($id_role == 6)
        echo "Foreman";
    elseif ($id_role == 9)
        echo "Manager";
    elseif ($id_role == 10)
        echo "Engineer";
    echo "</td>";
    echo "<td class='shif text-center'>" . $data_staff['id_shif'] . "</td>";
    echo "<td></td>";
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
require 'update/terminate.php';
?>