<?php
$total_break = strtotime("1970-01-01 " . $data_activity["total_break"] . " UTC");
$time_start = strtotime($data_activity["time_start"]);
$time_current = strtotime($data_time_current["CURRENT_TIME"]);
$time_total =  gmdate('H:i:s', $time_current-$time_start-$total_break);