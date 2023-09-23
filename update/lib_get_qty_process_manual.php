<?php
require '../const-status.php';
function get_qty_process_manual($conn, $id_task){

    $sql = "SELECT SUM(no_pulse2) AS qty_process, SUM(no_pulse3) AS qty_manual FROM activity
                WHERE id_task=" . $id_task;
    $data_activity = $conn->query($sql)->fetch_assoc();

    $sql = "SELECT SUM(no_pulse2) AS qty_process, SUM(no_pulse3) AS qty_manual FROM activity_rework 
                WHERE id_task=" . $id_task;
    $data_rework = $conn->query($sql)->fetch_assoc();

    $sql = "SELECT SUM(no_pulse2) AS qty_process, SUM(no_pulse3) AS qty_manual FROM activity_downtime 
                WHERE id_task=" . $id_task;
    $data_downtime = $conn->query($sql)->fetch_assoc();

    $qty_process =  intval($data_activity['qty_process']) +
                    intval($data_rework['qty_process']) +
                    intval($data_downtime['qty_process']);

    $qty_manual =   intval($data_activity['qty_manual']) +
                    intval($data_rework['qty_manual']) +
                    intval($data_downtime['qty_manual']);

    return array($qty_process, $qty_manual);
}
