<?php
ini_set('display_errors', 0);
error_reporting(E_ERROR | E_WARNING | E_PARSE);
require 'update/establish.php';
$sql = "SELECT * FROM staff WHERE id_role IN (SELECT id_role FROM role WHERE role_group=1) AND active=1 ORDER BY id_role ASC, id_staff ASC";

$result_staff = $conn->query($sql);

while($data_staff = $result_staff->fetch_assoc()) {
    echo "<tr class='text-black fw-bold row_staff'>";
    echo "<td class='id_staff'>" . $data_staff['id_staff'] . "</td>";
    echo "<td class='rfid'>" . $data_staff['id_rfid'] . "</tdclass>";
    echo "<td class='prefix'>";
    $prefix = intval($data_staff['prefix']);
    if ($prefix==1)
        echo "นาย";
    elseif ($prefix==2)
        echo "นาง";
    elseif ($prefix==3)
        echo "นางสาว";
    echo "<td>". $data_staff['name_first'] ." ". $data_staff['name_last']."</td>";

    echo "<td class='role'>";
    $id_role = intval($data_staff['id_role']);
    if ($id_role==1)
        echo "Operator";
    elseif ($id_role==3)
        echo "Production Support";
    elseif ($id_role==4)
        echo "Instructor";
    elseif ($id_role==5)
        echo "Senior Instructor";
    elseif ($id_role==6)
        echo "Foreman";
    elseif ($id_role==7)
        echo "Leader";
    elseif ($id_role==9)
        echo "Manager";
    elseif ($id_role==10)
        echo "Engineering";
    echo "</td>";
    echo "<td class='shif' >" . $data_staff['id_shif'] . "</td>";
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
                        class="btn btn-datatable btn-icon text-black me-2 user_delete" 
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
