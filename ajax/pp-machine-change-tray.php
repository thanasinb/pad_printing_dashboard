<?php
require '../update/establish.php';

$sql = "UPDATE planning SET qty_per_pulse2='" . $_GET['qty_per_tray'] . "'WHERE id_task='" . $_GET['id_task'] . "'";

$result_staff = $conn->query($sql);

require '../update/terminate.php';

echo json_encode(array("statusCode" => 200), JSON_UNESCAPED_UNICODE);

?>
