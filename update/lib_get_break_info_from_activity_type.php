<?php
function get_break_info_from_activity_type($activity_type)
{
    if($activity_type==3){
        $table = 'break_downtime';
    }else{
        if($activity_type==1){
            $table = 'break';
        }else{
            $table = 'break_rework';
        }
    }

    return $table;
}