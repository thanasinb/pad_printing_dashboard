<?php
function get_pulse_from_duration($conn, $id_task, $id_mc, $time1, $time2){

    $sql = "SELECT SUM(no_pulse1) AS qty_shif_pulse1, 
            SUM(no_pulse2) AS qty_shif_pulse2, 
            SUM(no_pulse3) AS qty_shif_pulse3 FROM activity 
            WHERE id_machine='" . $id_mc . "' AND " .
            "id_task=" . $id_task . " AND " .
            "(time_start BETWEEN '" . date("Y-m-d H:i:s", $time1) . "' AND '" .
            date("Y-m-d H:i:s", $time2) . "')";

//    echo $sql . "<br>";
    $data_activity = $conn->query($sql)->fetch_assoc();

    $sql = "SELECT SUM(no_pulse1) AS qty_shif_pulse1,
            SUM(no_pulse2) AS qty_shif_pulse2,
            SUM(no_pulse3) AS qty_shif_pulse3 FROM activity_rework
            WHERE id_machine='" . $id_mc . "' AND " .
            "id_task=" . $id_task . " AND " .
            "(time_start BETWEEN '" . date("Y-m-d H:i:s", $time1) . "' AND '" .
            date("Y-m-d H:i:s", $time2) . "')";
    $data_rework = $conn->query($sql)->fetch_assoc();

    $sql = "SELECT SUM(no_pulse1) AS qty_shif_pulse1,
            SUM(no_pulse2) AS qty_shif_pulse2,
            SUM(no_pulse3) AS qty_shif_pulse3 FROM activity_downtime
            WHERE id_machine='" . $id_mc . "' AND " .
            "id_task=" . $id_task . " AND " .
            "(time_start BETWEEN '" . date("Y-m-d H:i:s", $time1) . "' AND '" .
            date("Y-m-d H:i:s", $time2) . "')";
    $data_downtime = $conn->query($sql)->fetch_assoc();

    if ($data_activity['qty_shif_pulse1'] == null){
        $data_activity['qty_shif_pulse1'] = 0;
    }
    if ($data_activity['qty_shif_pulse2'] == null){
        $data_activity['qty_shif_pulse2'] = 0;
    }
    if ($data_activity['qty_shif_pulse3'] == null){
        $data_activity['qty_shif_pulse3'] = 0;
    }
    if ($data_rework['qty_shif_pulse1'] == null){
        $data_rework['qty_shif_pulse1'] = 0;
    }
    if ($data_rework['qty_shif_pulse2'] == null){
        $data_rework['qty_shif_pulse2'] = 0;
    }
    if ($data_rework['qty_shif_pulse3'] == null){
        $data_rework['qty_shif_pulse3'] = 0;
    }
    if ($data_downtime['qty_shif_pulse1'] == null){
        $data_downtime['qty_shif_pulse1'] = 0;
    }
    if ($data_downtime['qty_shif_pulse2'] == null){
        $data_downtime['qty_shif_pulse2'] = 0;
    }
    if ($data_downtime['qty_shif_pulse3'] == null){
        $data_downtime['qty_shif_pulse3'] = 0;
    }

    return  intval($data_activity['qty_shif_pulse2']) + intval($data_activity['qty_shif_pulse3']) +
            intval($data_rework['qty_shif_pulse2']) + intval($data_rework['qty_shif_pulse3']) +
            intval($data_downtime['qty_shif_pulse2']) + intval($data_downtime['qty_shif_pulse3']);
}
