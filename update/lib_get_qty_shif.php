<?php
require 'lib_get_pulse_from_duration.php';
function get_qty_shif($conn, $id_task, $id_mc){
    $time_current = time();
    $today_00_00 = strtotime(date("Y-m-d", $time_current));

    $day_1 = 24*60*60;
    $today_07_00 = $today_00_00 + (7*60*60);
    $today_19_00 = $today_00_00 + (19*60*60);
    $yesterday_19_00 = $today_00_00 + (19*60*60) - $day_1;
    $midnight = $today_00_00 + $day_1;

    //NIGHT SHIF (W AND W/O OT) BTW MIDNIGHT AND 07:00 TODAY
    if ($today_00_00<=$time_current AND $time_current<$today_07_00){
        $qty_shif = get_pulse_from_duration($conn, $id_task, $id_mc, $yesterday_19_00, $today_07_00);
    }
    // NIGHT SHIF BTW 19:00 TO MIDNIGHT
    elseif ($today_19_00<=$time_current AND $time_current<$midnight){
        $qty_shif = get_pulse_from_duration($conn, $id_task, $id_mc, $today_19_00, $midnight);
    }
    //DAY SHIF BTW 07:00 AND 19:00
    elseif ($today_07_00<=$time_current AND $time_current<$today_19_00){
        $qty_shif = get_pulse_from_duration($conn, $id_task, $id_mc, $today_07_00, $today_19_00);
    }

    return $qty_shif;
}
