<?php
function find_qty_shif($conn, $id_staff, $id_task){

    $time_current = time();
    $today_00_00 = strtotime(date("Y-m-d", $time_current));

    $day_1 = 24*60*60;
    $today_07_00 = $today_00_00 + (7*60*60);
    $today_19_00 = $today_00_00 + (19*60*60);
    $yesterday_19_00 = $today_00_00 + (19*60*60) - $day_1;

    //NIGHT SHIF (W AND W/O OT) BTW 19:00 YESTERDAY AND 07:00 TODAY
    if ($yesterday_19_00<=$time_current AND $time_current<$today_07_00){
        $sql = "SELECT SUM(no_pulse1) AS qty_shif FROM activity WHERE status_work<6 AND " .
            "id_task=" . $id_task . " AND " .
            "id_staff='" . $id_staff . "' AND " .
            "(time_start BETWEEN '" . date("Y-m-d H:i:s", $yesterday_19_00) . "' AND '" . date("Y-m-d H:i:s", $today_07_00) . "')";
        $result = $conn->query($sql);
        $data_activity = $result->fetch_assoc();

        //DAY SHIF BTW 07:00 AND 19:00
    }elseif ($today_07_00<=$time_current AND $time_current<$today_19_00){
        $sql = "SELECT SUM(no_pulse1) AS qty_shif FROM activity WHERE status_work<6 AND " .
            "id_task=" . $id_task . " AND " .
            "id_staff='" . $id_staff . "' AND " .
            "(time_start BETWEEN '" . date("Y-m-d H:i:s", $today_07_00) . "' AND '" . date("Y-m-d H:i:s", $today_19_00) . "')";
        $result = $conn->query($sql);
        $data_activity = $result->fetch_assoc();
    }
    return $data_activity;
}
