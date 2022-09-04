<?php
function get_break_info_from_activity_type($activity_type)
{
    if($activity_type==3){
        $table = 'activity_downtime';
        $table_break = 'break_downtime';
        $str_status = 'status_downtime';
        $str_activity = 'id_activity_downtime';
    }else{
        $str_status = 'status_work';
        $str_activity = 'id_activity';
        if($activity_type==1){
            $table = 'activity';
            $table_break = 'break';
        }else{
            $table = 'activity_rework';
            $table_break = 'break_rework';
        }
    }

    return array($table, $table_break, $str_activity, $str_status);
}