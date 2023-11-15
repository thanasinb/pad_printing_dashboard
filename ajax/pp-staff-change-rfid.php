<?php
require '../update/establish.php';
require '../update/lib_get_staff_by_rfid.php';

$data_staff_rfid = get_staff_by_rfid($conn, $_GET['id_rfid']);
if(empty($data_staff_rfid)){
    $sql = "UPDATE staff SET id_rfid=
    '" . $_GET['id_rfid'] . "',
    name_first='".$_GET['name_first']."',
    name_last='".$_GET['name_last']."',
    prefix='".$_GET['prefix']."',
    id_role='".$_GET['id_role']."',
    id_shif='".$_GET['id_shif']."',
    site='".$_GET['site']."'WHERE id_staff='" . $_GET['id_staff'] . "'";

    $result_staff = $conn->query($sql);
    $status_code = 200;
}else{
    $status_code = 30;
}

require '../update/terminate.php';

echo json_encode(array("statusCode" => $status_code), JSON_UNESCAPED_UNICODE);

?>
