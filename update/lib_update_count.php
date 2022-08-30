<?php
function update_count($conn, $table, $id_activity, $no_send, $no_pulse1, $no_pulse2, $no_pulse3, $multiplier){

    if(strcmp('activity_downtime', $table) == 0){
        $status_work_text = 'status_downtime=1,';
        $id_activity_text = 'id_activity_downtime';
    }else{
        $id_activity_text = 'id_activity';
        $status_work_text = 'status_work=1,';
    }

    $sql = "SELECT time_start, total_work, total_food, total_toilet, no_pulse1, no_pulse2, no_pulse3, 
       CURRENT_TIMESTAMP() AS time_current FROM " . $table . " WHERE " . $id_activity_text . "=" . $id_activity;
    $result = $conn->query($sql);
    $data_activity = $result->fetch_assoc();

    $total_food = strtotime("1970-01-01 " . $data_activity["total_food"] . " UTC");
    $total_toilet = strtotime("1970-01-01 " . $data_activity["total_toilet"] . " UTC");
    $total_break = $total_food + $total_toilet;
    $time_start = strtotime($data_activity["time_start"]);
    $time_current = strtotime($data_activity["time_current"]);
    $time_total_second = $time_current - $time_start - $total_break;
    $time_total =  gmdate('H:i:s', $time_total_second);

//    $no_pulse1 = $no_pulse1 + intval($data_activity['no_pulse1']) ;
//    $no_pulse2 = $no_pulse2 + intval($data_activity['no_pulse2']) ;
//    $no_pulse3 = $no_pulse3 + intval($data_activity['no_pulse3']) ;
    $count_accum = $no_pulse2 + $no_pulse3;

    if($count_accum==0){
        $run_time_actual=0.0;
    }else{
        $count_accum = floatval($count_accum);
        $run_time_actual = round($time_total_second/$count_accum, 2);
    }

    $sql = "UPDATE " . $table . " SET ";
    $sql = $sql . $status_work_text;
    $sql = $sql . "total_work='" . $time_total . "',";
    $sql = $sql . "run_time_actual=" . $run_time_actual . ",";
    $sql = $sql . "no_send=" . $no_send . ",";
    $sql = $sql . "no_pulse1=" . $no_pulse1 . ",";
    $sql = $sql . "no_pulse2=" . $no_pulse2 . ",";
    $sql = $sql . "no_pulse3=" . $no_pulse3 . ",";
    $sql = $sql . "multiplier=" . $multiplier;
    $sql = $sql . " WHERE " . $id_activity_text . "=" . $id_activity;
    $result = $conn->query($sql);
//    echo $sql;

    $data_json = json_encode(array('code'=>'200', 'message'=>'OK'), JSON_UNESCAPED_UNICODE);

    return $data_json;
}
