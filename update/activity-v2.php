<?php

$time_current = time();
$today_00_00 = strtotime(date("Y-m-d", $time_current));
$today_07_00 = $today_00_00 + (7*60*60);
$today_15_45 = $today_00_00 + (15*60*60) + (45*60);
$today_19_00 = $today_00_00 + (19*60*60);
$today_23_00 = $today_00_00 + (23*60*60);
$tomorrow_07_00 = $today_07_00 + (24*60*60);

if ($today_07_00<$time_current AND $time_current<$today_15_45){
    echo "morning";
}elseif ($today_15_45<$time_current AND $time_current<$today_19_00){
    echo "morning_ot";
}elseif ($today_19_00<$time_current AND $time_current<$today_23_00){
    echo "night_ot";
}elseif ($today_23_00<$time_current AND $time_current<$tomorrow_07_00){
    echo "night";
}

//echo $today_00_00 . "<br>";
//echo $today_07_00 . "<br>";
//echo $today_15_45 . "<br>";

?>