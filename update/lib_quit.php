<?php
function quit($conn,
              $id_mc,
              $id_rfid,
              $id_activity,
              $activity_type,
              $no_send,
              $no_pulse1,
              $no_pulse2,
              $no_pulse3,
              $multiplier){
    $data_staff_rfid = get_staff_by_rfid($conn, $id_rfid);
    if(empty($data_staff_rfid)){
        $data_json = json_encode(array('code'=>'011', 'message'=>'Invalid RFID'), JSON_UNESCAPED_UNICODE);
//        print_r($data_json);
    }
    else {
        list($table, $str_activity, $str_status) = get_info_from_activity_type($activity_type);
        $data_activity = get_active_activity_by_activity_id_type_staff_and_machine(
            $conn,
            $table,
            $str_activity,
            $str_status,
            $id_activity,
            $data_staff_rfid['id_staff'],
            $id_mc);
        if(empty($data_activity)) {
            $data_json = json_encode(array("code"=>"012", 'message'=>'Activity mismatches'), JSON_UNESCAPED_UNICODE);
//            print_r($data_json);
        } else {
            $is_quit = true;
            $status_work = 3;
            $data_json = update_count($conn, $is_quit, $table, $status_work,
                $id_activity,
                $no_send,
                $no_pulse1,
                $no_pulse2,
                $no_pulse3,
                $multiplier);

            $sql = "UPDATE machine_queue SET id_staff='', id_activity=0, activity_type=0 WHERE id_machine='" . $id_mc . "' AND queue_number=1";
            $result = $conn->query($sql);

            $data_json = json_encode(array_merge(json_decode($data_json, true), array("quit_complete"=>true)));
        }
    }
    return $data_json;
}