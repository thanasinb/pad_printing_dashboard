<?php
ini_set('display_errors', 0);
error_reporting(E_ERROR | E_WARNING | E_PARSE);
require 'update/establish.php';
$sql = "SELECT * FROM code_downtime WHERE enable=1 ORDER BY enable DESC, id_code_downtime ASC";
$result_code = $conn->query($sql);

while($data_code = $result_code->fetch_assoc()) {
    echo "<tr class='text-black fw-bold row_downtime'>";
    echo "<td class='box_code text-center'>" . $data_code['id_code_downtime'] . "</td>";
    echo "<td class='code_downtime text-center'>" . $data_code['code_downtime'] . "</td>";
    echo "<td class='des_downtime_eng text-center'>" . $data_code['des_downtime'] . "</td>";
    echo "<td class='des_downtime_tha text-center'>" . $data_code['des_downtime_thai'] . "</td>";

    $date_valid = strcmp($data_code['date_setting'], '0000-00-00 00:00:00');

    echo "<td class='date_setting text-center'>";
    if ($date_valid != 0) {
        echo $data_code['date_setting'];
    }
    echo "</td>";
    echo "<td class='text-center'>
            <button type='button' 
                    name='downtime_edit' 
                    data-bs-toggle='modal' 
                    data-bs-target='#setting_dt_modal' 
                    class='btn btn-datatable btn-icon text-black me-2 downtime_edit'>
                <i class='far fa-edit fs-6'></i>
            </button>
            <button type='button' 
                    data-bs-toggle='modal' 
                    data-bs-target='#delete_dt_modal' 
                    class='btn btn-datatable btn-icon text-black me-2 downtime_delete'>
                <i class='fas fa-trash'></i>
            </button>
          </td>";
    echo "</tr>";
}
require 'update/terminate.php';
?>