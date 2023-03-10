<?php
function get_shif($conn, $id_staff, $team){

    if(strcmp($team, 'A') == 0){
        $team_no = '4';
    }elseif (strcmp($team, 'B') == 0){
        $team_no = '6';
    }elseif (strcmp($team, 'C') == 0) {
        $team_no = '5';
    }

    $sql = "SELECT value AS shif_offset_minute FROM settings WHERE name='shif_offset_minute'";
    $result = $conn->query($sql);
    $data_settings = $result->fetch_assoc();
    $shif_offset_second = intval($data_settings['shif_offset_minute']) * 60;

    $time_current = time();
    $date_eff = date("Y-m-d");

    $today_00_00 = strtotime(date("Y-m-d", $time_current));
//    echo $today_00_00;

    $day_1 = 24*60*60;
    $today_07_00 = $today_00_00 + (7*60*60);
    $today_15_45 = $today_00_00 + (15*60*60) + (45*60);
    $today_19_00 = $today_00_00 + (19*60*60);
    $today_23_00 = $today_00_00 + (23*60*60);
    $tomorrow_00_00 = $today_00_00 + $day_1;
    $yesterday_19_00 = $today_00_00 + (19*60*60) - $day_1;
    $yesterday_23_00 = $today_00_00 + (23*60*60) - $day_1;

    //NIGHT SHIF BTW 00:00 AND 07:00, NEED TO CHECK YESTERDAY 19:00-23:00 FOR OT
    if ($today_00_00<$time_current AND $time_current<$today_07_00){
        $sql = "SELECT id_activity FROM activity WHERE id_staff='" . $id_staff . "' " .
            "AND (time_start BETWEEN '" . date("Y-m-d H:i:s", $yesterday_19_00 - $shif_offset_second) . "' " .
            "AND '" . date("Y-m-d H:i:s", $yesterday_23_00) . "')";
        $result = $conn->query($sql);
        $data_activity = $result->fetch_assoc();
        if (empty($data_activity)){
            // W/O OT, NUMBER BEFORE 'N'
            $shif = "N" . $team_no;
        }else{
            // WITH OT, NUMBER AFTER 'N'
            $shif = $team_no . "N";
        }
        $date_eff = date('Y-m-d',strtotime("-1 days"));

        //DAY SHIF BTW 07:00 AND 15:45
    }elseif ($today_07_00<$time_current AND $time_current<$today_15_45){
        $shif = "D" . $team_no;
    }elseif ($today_15_45<$time_current AND $time_current<$today_19_00){
        $shif = $team_no . "D";
        $sql = "UPDATE activity SET shif='" . $shif . "' WHERE id_staff='" . $id_staff . "'" .
            " AND (time_start BETWEEN '" . date("Y-m-d H:i:s", $today_07_00 - $shif_offset_second) . "' AND '" . date("Y-m-d H:i:s", $today_15_45) . "')";
        $conn->query($sql);
//        echo $sql;
    }elseif ($today_19_00<$time_current AND $time_current<$today_23_00){
        $shif = $team_no . "N";
    }elseif ($today_23_00<$time_current AND $time_current<$tomorrow_00_00){
        $sql = "SELECT id_activity FROM activity WHERE id_staff='" . $id_staff . "'" .
            " AND (time_start BETWEEN '" . date("Y-m-d H:i:s", $today_19_00 - $shif_offset_second) . "' AND '" . date("Y-m-d H:i:s", $today_23_00) . "')";
        $result = $conn->query($sql);
        $data_activity = $result->fetch_assoc();
        if (empty($data_activity)){
            $shif = "N" . $team_no;
        }else{
            $shif = $team_no . "N";
        }
    }
    return array($shif, $date_eff);
}
