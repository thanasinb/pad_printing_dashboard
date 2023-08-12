<?php

$error_code=0;

require 'update/establish.php';

$sql = "SELECT id_code_downtime FROM code_downtime WHERE id_code_downtime='" . $_POST['box_code'] . "'";
$data_code_downtime = $conn->query($sql)->fetch_assoc();

if (empty($data_code_downtime['id_code_downtime'])){
    $sql = "INSERT INTO code_downtime (
                           id_code_downtime, 
                           code_downtime, 
                           des_downtime, 
                           des_downtime_thai, 
                           enable, 
                           date_setting) VALUES (".
                            "'".$_POST['box_code']."',".
                            "'".$_POST['downtime_code']."',".
                            "'".$_POST['description_eng']."',".
                            "'".$_POST['description_tha']."',1,(SELECT CURRENT_TIMESTAMP))";
    echo $sql;
    $result = $conn->query($sql);
}else{
    $error_code = 603;
}

require 'update/terminate.php';

header("Location: ./pp-setting-dt-add.php?error_code=" . $error_code);
die();

?>