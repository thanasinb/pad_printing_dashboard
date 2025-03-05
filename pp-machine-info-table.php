<?php
ini_set('display_errors', 0);
error_reporting(E_ERROR | E_WARNING | E_PARSE);
require 'update/establish.php';
$sql = "SELECT * , machine_type.mc_type FROM machine
                    left join machine_type ON machine.id_mc_type = machine_type.id_mc_type";
$result_code = $conn->query($sql);

while($data_code = $result_code->fetch_assoc()) {
    echo "<tr class='text-black fw-bold row_mc'>";
    echo "<td class='id_mc text-center'>" . $data_code['id_mc'] . "</td>";
    echo "<td class='id_mc_type text-center' data-id-mc-type='" . $data_code['id_mc_type'] . "'>" . $data_code['mc_type'] . "</td>";

    echo "<td class='id_cam text-center'>" . $data_code['id_cam'] . "</td>";
    echo "<td class='mc_des text-center'>" . $data_code['mc_des'] . "</td>";
    echo "<td class='time_contact text-center'>" . $data_code['time_contact'] . "</td>";

//    echo "</td>";
    echo "<td class='text-center'>
            <button type='button' 
                    name='machine_info_edit' 
                    data-bs-toggle='modal' 
                    data-bs-target='#setting_mc_modal' 
                    class='btn btn-datatable btn-icon text-black me-2 mc_info_edit'>
                <i class='far fa-edit fs-6'></i>
            </button>
            <button type='button' 
                    data-bs-toggle='modal' 
                    data-bs-target='#delete_mc_modal' 
                    class='btn btn-datatable btn-icon text-black me-2 mc_info_delete'>
                <i class='fas fa-trash'></i>
            </button>
          </td>";
    echo "</tr>";
}
require 'update/terminate.php';
?>