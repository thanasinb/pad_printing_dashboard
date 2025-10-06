<?php
function get_info_from_activity_type($activity_type)
{
    if($activity_type==3){
        $table = 'activity_downtime';
        $str_status = 'status_downtime';
        $str_activity = 'id_activity_downtime';
    }elseif($activity_type==2){
        $table = 'activity_rework';
        $str_status = 'status_work';
        $str_activity = 'id_activity';
    }elseif ($activity_type==1){
        $table = 'activity';
        $str_status = 'status_work';
        $str_activity = 'id_activity';
    }elseif ($activity_type==4){
        $table = 'activity_calltech';
        $str_status = 'status_work';
        $str_activity = 'id_activity';
    }

    return array($table, $str_activity, $str_status);
}