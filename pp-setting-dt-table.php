<?php
ini_set('display_errors', 0);
error_reporting(E_ERROR | E_WARNING | E_PARSE);
require 'update/establish.php';
$sql = "SELECT * FROM code_downtime ORDER BY enable DESC, id_code_downtime ASC";
$result_code = $conn->query($sql);

while($data_code = $result_code->fetch_assoc()) {
    echo "<tr class='text-black fw-bold row_downtime'>";
    echo "<td class='id_code_downtime'>" . $data_code['id_code_downtime'] . "</td>";
    echo "<td class='code_downtime'>" . $data_code['code_downtime'] . "</td>";
    echo "<td class='des_downtime'>" . $data_code['des_downtime'] . "</td>";
    echo "<td class='des_downtime_thai'>" . $data_code['des_downtime_thai'] . "</td>";
    echo "<td class='status_enable'>";
    $status_enable = intval($data_code['enable']);
    if ($status_enable==1)
        echo "Y";
    elseif ($status_enable==0)
        echo "N";
    echo "</td>";

    $date_valid = strcmp($data_code['date_setting'], '0000-00-00 00:00:00');

    echo "<td class='id_staff'>";
    if ($date_valid!=0)
        echo $data_code['id_staff'];
    echo "</td>";
    echo "<td class='date_setting'>";
    if ($date_valid!=0)
        echo $data_code['date_setting'];
    echo "</td><td><button  type='button' 
                            name='downtime_edit' 
                            data-bs-toggle='modal' 
                            data-bs-target='#setting_dt_modal' 
                            class='btn btn-datatable btn-icon text-black me-2 downtime_edit'>";
    echo "<i class='far fa-edit fs-6'></i></button></td></tr>";
}
require 'update/terminate.php';
?>

