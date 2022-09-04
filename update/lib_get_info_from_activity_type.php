<?php
function get_info_from_activity_type($activity_type)
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

    return array($table, $str_activity, $str_status);
}