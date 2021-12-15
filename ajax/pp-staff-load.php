<?php
require '../update/establish.php';

//SELECT staff.id_staff, staff.id_rfid, prefix.prefix, staff.name_first, staff.name_last, staff.site, role.role, staff.id_shif FROM staff INNER JOIN role ON staff.id_role=role.id_role INNER JOIN prefix ON staff.prefix=prefix.id_prefix WHERE id_staff='0009'

$sql = "SELECT staff.id_staff, staff.id_rfid, prefix.prefix, staff.name_first, staff.name_last, staff.site, role.role, staff.id_shif FROM staff INNER JOIN role ON staff.id_role=role.id_role INNER JOIN prefix ON staff.prefix=prefix.id_prefix WHERE id_staff='" . $_GET['id_staff'] . "'";

$result = $conn->query($sql);
$db_staff = $result->fetch_assoc();

echo json_encode($db_staff, JSON_UNESCAPED_UNICODE);

require '../update/terminate.php';

//echo json_encode(array("id_staff"=>$_POST["id_staff"]));

?>

