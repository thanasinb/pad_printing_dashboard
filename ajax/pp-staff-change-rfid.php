<?php
require '../update/establish.php';

$sql = "UPDATE staff SET id_rfid=
'" . $_GET['id_rfid'] . "',
name_first='".$_GET['name_first']."',
name_last='".$_GET['name_last']."',
prefix='".$_GET['prefix']."',
id_role='".$_GET['id_role']."',
id_shif='".$_GET['id_shif']."',
site='".$_GET['site']."'WHERE id_staff='" . $_GET['id_staff'] . "'";

$result_staff = $conn->query($sql);

require '../update/terminate.php';

echo json_encode(array("statusCode" => 200), JSON_UNESCAPED_UNICODE);

?>
