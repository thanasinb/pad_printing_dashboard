<?php
require '../update/establish.php';
require '../const-status.php';

$sql = "UPDATE planning SET qty_per_pulse2='" . $_GET['qty_per_tray'] . " 'WHERE id_task='" . $_GET['id_task'] . "'";
$result_staff = $conn->query($sql);

$sql = "UPDATE activity SET no_pulse2=" . $_GET['qty_shif'] . ", no_pulse3=0 WHERE id_task='" . $_GET['id_task'] . "' AND status_work<" . STATUS_CLOSED;
$result_activity = $conn->query($sql);
$affected_rows = $conn->affected_rows;
if ($affected_rows==0){
    $sql = "UPDATE activity_rework SET no_pulse2=" . $_GET['qty_shif'] . ", no_pulse3=0 WHERE id_task='" . $_GET['id_task'] . "' AND status_work<" . STATUS_CLOSED;
    $result_activity = $conn->query($sql);
    $affected_rows = $conn->affected_rows;
}
if ($affected_rows==0){
    $sql = "UPDATE activity_downtime SET no_pulse2=" . $_GET['qty_shif'] . ", no_pulse3=0 WHERE id_task='" . $_GET['id_task'] . "' AND status_downtime<" . STATUS_CLOSED;
    $result_activity = $conn->query($sql);
    $affected_rows = $conn->affected_rows;
}

require '../update/terminate.php';

echo json_encode(array("statusCode" => 200, "affected_rows" => $affected_rows), JSON_UNESCAPED_UNICODE);

?>
