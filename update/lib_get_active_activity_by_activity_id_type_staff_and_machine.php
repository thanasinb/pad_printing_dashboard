<?php
function get_active_activity_by_id_and_machine($conn, $id_activity, $activity_type, $id_staff, $id_mc)
{
    if($activity_type==3){
        $table = 'activity_downtime';
        $str_status = 'status_downtime';
        $str_activity = 'id_activity_downtime';
    }else{
        $str_status = 'status_work';
        $str_activity = 'id_activity';
        if($activity_type==1){
            $table = 'activity';
        }else{
            $table = 'activity_rework';
        }
    }
    $sql = "SELECT * FROM " . $table . " WHERE " .
        $str_activity . "=" . $id_activity . " AND 
        id_staff = " . $id_staff . " AND 
        id_machine = '" . $id_mc . "' AND " .
        $str_status . "=1";
    $result = $conn->query($sql);
    $data = $result->fetch_assoc();
    return $data;
}