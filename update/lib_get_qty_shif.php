<?php
//require 'lib_add_log.php';
function get_qty_shif($conn, $table, $str_status, $id_staff, $id_task){
    $time_current = time();
    $today_00_00 = strtotime(date("Y-m-d", $time_current));

    $day_1 = 24*60*60;
    $today_07_00 = $today_00_00 + (7*60*60);
    $today_19_00 = $today_00_00 + (19*60*60);
    $yesterday_19_00 = $today_00_00 + (19*60*60) - $day_1;
    $midnight = $today_00_00 + $day_1;

//    echo "hello1";
    //NIGHT SHIF (W AND W/O OT) BTW MIDNIGHT AND 07:00 TODAY
    if ($today_00_00<=$time_current AND $time_current<$today_07_00){
//        echo "hello2";
        $sql = "SELECT SUM(no_pulse1) AS qty_shif_pulse1, SUM(no_pulse2) AS qty_shif_pulse2, SUM(no_pulse3) AS qty_shif_pulse3 FROM " . $table . " WHERE " . $str_status . "<6 AND " .
            "id_task=" . $id_task . " AND " .
            "id_staff='" . $id_staff . "' AND " .
            "(time_start BETWEEN '" . date("Y-m-d H:i:s", $yesterday_19_00) . "' AND '" . date("Y-m-d H:i:s", $today_07_00) . "')";
//        echo $sql . " Night <br>";
        add_log($conn, $sql);
        $result = $conn->query($sql);
        $data_activity = $result->fetch_assoc();
    }
    // NIGHT SHIF BTW 19:00 TO MIDNIGHT
    elseif ($today_19_00<=$time_current AND $time_current<$midnight){
//        echo "hello4";
        $sql = "SELECT SUM(no_pulse1) AS qty_shif_pulse1, SUM(no_pulse2) AS qty_shif_pulse2, SUM(no_pulse3) AS qty_shif_pulse3 FROM " . $table . " WHERE " . $str_status . "<6 AND " .
            "id_task=" . $id_task . " AND " .
            "id_staff='" . $id_staff . "' AND " .
            "(time_start BETWEEN '" . date("Y-m-d H:i:s", $today_19_00) . "' AND '" . date("Y-m-d H:i:s", $midnight) . "')";
//        echo $sql . " Night <br>";
        add_log($conn, mysqli_real_escape_string($conn, $sql));
        $result = $conn->query($sql);
        $data_activity = $result->fetch_assoc();
    }
    //DAY SHIF BTW 07:00 AND 19:00
    elseif ($today_07_00<=$time_current AND $time_current<$today_19_00){
//        echo "hello3";
        $sql = "SELECT SUM(no_pulse1) AS qty_shif_pulse1, SUM(no_pulse2) AS qty_shif_pulse2, SUM(no_pulse3) AS qty_shif_pulse3 FROM " . $table . " WHERE " . $str_status . "<6 AND " .
            "id_task=" . $id_task . " AND " .
            "id_staff='" . $id_staff . "' AND " .
            "(time_start BETWEEN '" . date("Y-m-d H:i:s", $today_07_00) . "' AND '" . date("Y-m-d H:i:s", $today_19_00) . "')";
//        echo $sql . " Day <br>";
        add_log($conn, $sql);
        $result = $conn->query($sql);
        $data_activity = $result->fetch_assoc();
    }
    if ($data_activity['qty_shif_pulse1'] == null){
        $data_activity['qty_shif_pulse1'] = 0;
    }
    if ($data_activity['qty_shif_pulse2'] == null){
        $data_activity['qty_shif_pulse2'] = 0;
    }
    if ($data_activity['qty_shif_pulse3'] == null){
        $data_activity['qty_shif_pulse3'] = 0;
    }
    return $data_activity;
}
