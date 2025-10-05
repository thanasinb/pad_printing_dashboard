<?php
function update_count_reset($conn, $is_quit, $table, $str_status, $str_activity, $data_activity){
    $total_food = strtotime("1970-01-01 " . $data_activity['total_food'] . " UTC");
    $total_toilet = strtotime("1970-01-01 " . $data_activity['total_toilet'] . " UTC");
    $total_break = $total_food;
    $time_start = strtotime($data_activity["time_start"]);
    $time_current = strtotime($data_activity["time_current"]);
    $time_total_second = $time_current - $time_start - $total_break;
    $time_total =  gmdate('H:i:s', $time_total_second);
    $time_previous = strtotime($data_activity["time_previous"]);
    $time_tray_second = $time_current - $time_previous;

    $count_accum = $data_activity['no_pulse2'] + $data_activity['no_pulse3'];

    if($count_accum==0){
        $run_time_actual=0.0;
        $run_time_tray=0.0;
    }else{
        $count_accum = floatval($count_accum);
        $run_time_actual = round($time_total_second/$count_accum, 2);
    }

    $sql = "UPDATE " . $table . " SET ";
    $sql = $sql . $str_status . "=3,";
    if ($is_quit){
        $sql = $sql . "time_close='" . $data_activity["time_current"] . "',";
    }
    $sql = $sql . "time_previous=CURRENT_TIMESTAMP(),";
    $sql = $sql . "total_work='" . $time_total . "',";
    $sql = $sql . "run_time_actual=" . $run_time_actual;
    $sql = $sql . " WHERE " . $str_activity . "=" . $data_activity[$str_activity];

    $result = $conn->query($sql);

    $data_json = json_encode(array(
        'code'=>'200',
        'message'=>'OK',
        'count_accum' => $count_accum,
        'time_start' => $data_activity["time_start"],
        'time_current' => $data_activity["time_current"],
        'total_food' => $data_activity["total_food"],
        'total_toilet' => $data_activity["total_toilet"],
        'time_total_second' => $time_total_second,
        'time_work' => $time_total,
        'run_time_actual' => $run_time_actual,
        'run_time_tray' => $run_time_tray
    ), JSON_UNESCAPED_UNICODE);

    return $data_json;
}
