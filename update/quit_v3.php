<?php
require 'establish.php';
require 'lib_get_staff_by_rfid.php';
require 'lib_get_info_from_activity_type.php';
require 'lib_get_active_activity_by_activity_id_type_staff_and_machine.php';

$data_staff_rfid = get_staff_by_rfid($conn, $_GET['id_rfid']);
if(empty($data_staff_rfid)){
    $data_json = json_encode(array('code'=>'011', 'message'=>'Invalid RFID'), JSON_UNESCAPED_UNICODE);
    print_r($data_json);
}
else {
    list($table, $str_activity, $str_status) = get_info_from_activity_type($_GET['activity_type']);
    $data_activity = get_active_activity_by_activity_id_type_staff_and_machine(
        $conn,
        $table,
        $str_activity,
        $str_status,
        $_GET['id_activity'],
        $data_staff_rfid['id_staff'],
        $_GET['id_mc']);
    if(empty($data_activity)) {
        $data_json = json_encode(array("code"=>"012", 'message'=>'Activity mismatches'), JSON_UNESCAPED_UNICODE);
        print_r($data_json);
    } else {
        $sql = "SELECT op_color, op_side, qty_per_pulse2 FROM planning WHERE id_task=" . $data_activity['id_task'];
        $result = $conn->query($sql);
        $data_planning = $result->fetch_assoc();

        $sql = "SELECT divider AS multiplier FROM divider WHERE
                                              op_color=" . $data_planning['op_color'] . " AND
                                              op_side='" . $data_planning['op_side'] . "'";
        $result = $conn->query($sql);
        $data_multiplier = $result->fetch_assoc();

        $total_food = strtotime("1970-01-01 " . $data_activity["total_food"] . " UTC");
        $total_toilet = strtotime("1970-01-01 " . $data_activity["total_toilet"] . " UTC");
        $total_break = $total_food + $total_toilet;
        $time_start = strtotime($data_activity["time_start"]);
        $time_current = strtotime($data_activity["time_current"]);
        $time_total_second = $time_current - $time_start - $total_break;
        $time_total =  gmdate('H:i:s', $time_total_second);

        $no_pulse1 = floatval($data_activity['no_pulse1']) + ($_GET['no_pulse1'] * floatval($data_multiplier['multiplier']));
        $no_pulse2 = intval($data_activity['no_pulse2']) + ($_GET['no_pulse2'] * intval($data_planning['qty_per_pulse2']));
        $no_pulse3 = $_GET['no_pulse3'] + intval($data_activity['no_pulse3']) ;

        $count_accum = $no_pulse2 + $no_pulse3;

        if($count_accum==0){
            $run_time_actual=0.0;
        }else{
            $count_accum = floatval($count_accum);
            $run_time_actual = round($time_total_second/$count_accum, 2);
        }

        $sql = "UPDATE " . $table . " SET ";
        $sql = $sql . "status_work=3,";
        $sql = $sql . "total_work='" . $time_total . "',";
        $sql = $sql . "time_close='" . $data_activity["time_current"] . "',";
        $sql = $sql . "no_send=" . $_GET["no_send"] . ",";
        $sql = $sql . "no_pulse1=" . $no_pulse1 . ",";
        $sql = $sql . "no_pulse2=" . $no_pulse2 . ",";
        $sql = $sql . "no_pulse3=" . $no_pulse3;
        $sql = $sql . " WHERE id_activity=" . $data_activity["id_activity"];
        $result = $conn->query($sql);

        $data_json = json_encode(array(
            'time_work'=>$time_total,
            'code'=>'200',
            'message'=>'OK',
            'no_pulse1' => $no_pulse1,
            'no_pulse2' => $no_pulse2,
            'no_pulse3' => $no_pulse3
        ), JSON_UNESCAPED_UNICODE);

        if(empty($_GET['code_downtime'])) {
            $sql = "UPDATE machine_queue SET id_staff='' WHERE id_machine='" . $_GET["id_mc"] . "' AND queue_number=1";
            $result = $conn->query($sql);
            print_r($data_json);
        }
    }
}

require "contact.php";
require 'terminate.php';

