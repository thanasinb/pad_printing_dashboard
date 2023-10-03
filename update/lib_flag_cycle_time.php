<?php
function flag_cycle_time($cycle_tray, $cycle_shif, $cycle_std){

    $flag = 0;
    $cycle_shif = intval(floatval($cycle_shif)*100); // SHIF
    $cycle_tray = intval(floatval($cycle_tray)*100);
    $cycle_std = intval($cycle_std*100);
    if ($cycle_shif>$cycle_std){
        $flag++;
    }
    if($cycle_tray>$cycle_std){
        $flag=$flag+2;
    }
//    if($cycle_shif>$cycle_std and $cycle_tray>$cycle_std){
//        $flag++;
//    }

    return $flag;
}
