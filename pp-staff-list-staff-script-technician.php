<?php
ini_set('display_errors', 0);
error_reporting(E_ERROR | E_WARNING | E_PARSE);
require 'update/establish.php';
$sql = "SELECT * FROM staff WHERE id_role IN (SELECT id_role FROM role WHERE role_group=2) AND active=1 ORDER BY id_role ASC, id_staff ASC";
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
    if ($id_role==2)
        echo "Technician";
    elseif ($id_role==8)
        echo "Senior Technician";
    echo "</td>";
    echo "<td class='shif' >" . $data_staff['id_shif'] . "</td>";
    echo "<td>" . "<div class='avatar avatar-xl me-3 bg-gray-200'><img class='avatar-img img-fluid' src='./images/staffs/" . $data_staff['staff_img'] . "'  alt=' ' /></div>" . "</td>";
    echo "<td>";
    echo "<button name='staff_edit' type='submit' class='btn btn-datatable btn-icon text-black me-2 staff_edit'>";
    echo "<i class='far fa-edit fs-6'></i></button>";
    echo "<button name='staff_delete' type='submit' class='btn btn-datatable btn-icon text-black me-2 staff_delete'>";
    echo "<i class='fas fa-trash'></i></button>";
    echo "</td>";
    echo "</tr>";

}
require 'update/terminate.php';
?>
