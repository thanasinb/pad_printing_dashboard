<?php
require '../update/establish.php';

$sql = "SELECT id_code_downtime FROM code_downtime WHERE id_code_downtime='" . $_GET['box_code'] . "'";
$data_code_downtime = $conn->query($sql)->fetch_assoc();

if (empty($data_code_downtime['id_code_downtime'])){
    echo json_encode(array("statusCode" => 602, "message"=>"Box code not found."), JSON_UNESCAPED_UNICODE);
}else{
    $sql = "UPDATE code_downtime SET 
                         enable=0, 
                         date_setting= (SELECT CURRENT_TIMESTAMP) 
                         WHERE id_code_downtime='" . $_GET['box_code'] . "'";
    $result = $conn->query($sql);
    echo json_encode(array("statusCode" => 200, "message"=>"OK."), JSON_UNESCAPED_UNICODE);
}

require '../update/terminate.php';
?>
