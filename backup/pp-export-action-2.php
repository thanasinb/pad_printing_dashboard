<?php
ini_set('display_errors', 0);
error_reporting(E_ERROR | E_WARNING | E_PARSE);

include_once ("PHP_XLSXWriter/xlsxwriter.class.php");

const SHEET_BF_FIRST = 'CIM BF-first OP';
const SHEET_BF_NEXT = 'CIM BF-next OP';
const SHEET_DOWNTIME = 'CIM Down Time';
const SHEET_SCRAP = 'Cim Scarp';
const SHEET_SETUP = 'Cim Setup';
const SHEET_REWORK = 'Cim Rework';

function make_machine_name($id_machine){
    $id_machine = str_replace('-','',$id_machine);
    return ('PD0' . $id_machine);
}
function make_shif_code($id_shif, $shif_code){

    if ($shif_code==1){ // DAY WITH OT
        if (strcmp($id_shif, "A") == 0) {
            $id_shif = '4D';
        } elseif (strcmp($id_shif, "B") == 0) {
            $id_shif = '6D';
        } elseif (strcmp($id_shif, "C") == 0) {
            $id_shif = '5D';
        }
    }elseif ($shif_code==2){ // DAY W/O OT
        if (strcmp($id_shif, "A") == 0) {
            $id_shif = 'D4';
        } elseif (strcmp($id_shif, "B") == 0) {
            $id_shif = 'D6';
        } elseif (strcmp($id_shif, "C") == 0) {
            $id_shif = 'D5';
        }
    }elseif ($shif_code==3){ // NIGHT WITH OT
        if (strcmp($id_shif, "A") == 0) {
            $id_shif = '4N';
        } elseif (strcmp($id_shif, "B") == 0) {
            $id_shif = '6N';
        } elseif (strcmp($id_shif, "C") == 0) {
            $id_shif = '5N';
        }
    }elseif ($shif_code==4){ // NIGHT W/O OT
        if (strcmp($id_shif, "A") == 0) {
            $id_shif = 'N4';
        } elseif (strcmp($id_shif, "B") == 0) {
            $id_shif = 'N6';
        } elseif (strcmp($id_shif, "C") == 0) {
            $id_shif = 'N5';
        }
    }


//    else if($_GET['shif_number']==2){
//
//
//    }

//    echo $hour_start . "<br><br>";
    return $id_shif;
}
function make_first_operation_array($ar){
    $array_space=array('');
    $array_filler1=array('','','','','0','','','','','y');
    $array_filler2=array('','','','{space}','y','{f4}');
    array_splice( $ar, 8, 0, $array_space);
    array_splice( $ar, 8, 0, $array_space);
    array_splice( $ar, 12, 0, $array_space);
    array_splice( $ar, 14, 0, $array_space);
    array_splice( $ar, 15, 0, $array_filler1);
    array_splice( $ar, 26, 0, $array_filler2);
    return $ar;
}
function make_next_operation_array($ar){
    $array_space=array('');
    $array_filler1=array('','','','','0','','','','','y');
    $array_filler2=array('','','','{space}','{space}','y','{f4}');
    array_splice( $ar, 8, 0, $array_space);
    array_splice( $ar, 8, 0, $array_space);
    array_splice( $ar, 12, 0, $array_space);
    array_splice( $ar, 14, 0, $array_space);
    array_splice( $ar, 15, 0, $array_filler1);
    array_splice( $ar, 26, 0, $array_filler2);
    return $ar;
}
function make_downtime_array($ar){
    $array_space=array('');
//    $array_filler1=array('','','','','0','','','','','y');
    $array_filler2=array('{f1}','y','{f4}');
    array_splice( $ar, 8, 0, $array_space);
    array_splice( $ar, 8, 0, $array_space);
    array_splice( $ar, 12, 0, $array_space);
//    array_splice( $ar, 14, 0, $array_space);
    array_splice( $ar, 15, 0, $array_filler2);
//    array_splice( $ar, 26, 0, $array_filler2);
    return $ar;
}

function time2float($t){
    $parts = explode(':', $t);
    return $parts[0] + floor(($parts[1]/60)*100) / 100;
}

$writer = new XLSXWriter();
$header = array(
    'Employee'=>'string',
    'Document  (ID)'=>'string',
    'Effective date'=>'string',
    'Shift'=>'string',
    'Site'=>'string',
    'Item Number'=>'string',
    'Operation'=>'string',
    'Line'=>'string',
    'routing code'=>'string',
    'bomcode'=>'string',
    'Work Center'=>'string',
    'Machine'=>'string',
    'Department'=>'string',
    'qty process'=>'integer',
    'um'=>'string',
    'conversion'=>'string',
    'Qty scarp'=>'integer',
    'reason code scarp'=>'string',
    'Multi entry scarp'=>'string',
    'Qty reject'=>'integer',
    'reason code reject'=>'string',
    'Multi entry reject'=>'string',
    'reject to op'=>'string',
    'modify bf'=>'string',
    ' move next op'=>'string',
    'actual runtime'=>'string',
    'Earning code'=>'string',
    'start time'=>'string',
    'stop time'=>'string',
    '{space}'=>'string',
    'confirm'=>'string',
    '{f4}'=>'string'
);
$writer->writeSheetHeader(SHEET_BF_FIRST, $header, $suppress_row = false);

$header = array(
    'Employee'=>'string',
    'Document  (ID)'=>'string',
    'Effective date'=>'string',
    'Shift'=>'string',
    'Site'=>'string',
    'Item Number'=>'string',
    'Operation'=>'string',
    'Line'=>'string',
    'routing code'=>'string',
    'bomcode'=>'string',
    'Work Center'=>'string',
    'Machine'=>'string',
    'Department'=>'string',
    'qty process'=>'integer',
    'um'=>'string',
    'conversion'=>'string',
    'Qty scarp'=>'integer',
    'reason code scarp'=>'string',
    'Multi entry scarp'=>'string',
    'Qty reject'=>'integer',
    'reason code reject'=>'string',
    'Multi entry reject'=>'string',
    'reject to op'=>'string',
    'modify bf'=>'string',
    ' move next op'=>'string',
    'actual runtime'=>'string',
    'Earning code'=>'string',
    'start time'=>'string',
    'stop time'=>'string',
    '{space}'=>'string',
    '{space} '=>'string',
    'confirm'=>'string',
    '{f4}'=>'string'
);
$writer->writeSheetHeader(SHEET_BF_NEXT, $header, $suppress_row = false);

$header = array(
    'Employee'=>'string',
    'Document  (ID)'=>'string',
    'Effective date'=>'string',
    'Shift'=>'string',
    'Site'=>'string',
    'Item Number'=>'string',
    'Operation'=>'string',
    'Line'=>'string',
    'routing code'=>'string',
    'bomcode'=>'string',
    'Work Center'=>'string',
    'Machine'=>'string',
    'Department'=>'string',
    'actual runtime'=>'string',
    'Reason Code'=>'string',
    '{f1} '=>'string',
    'confirm'=>'string',
    '{f4}'=>'string'
);
$writer->writeSheetHeader(SHEET_DOWNTIME, $header);

$header = array(
    'Employee'=>'string',
    'Document  (ID)'=>'string',
    'Effective date'=>'string',
    'Shift'=>'string',
    'Site'=>'string',
    'Item Number'=>'string',
    'Operation'=>'string',
    'Line'=>'string',
    'routing code'=>'string',
    'bomcode'=>'string',
    'Work Center'=>'string',
    'Machine'=>'string',
    'Department'=>'string',
    'actual runtime'=>'string',
    '{f1} '=>'string',
    'confirm'=>'string',
    '{f4}'=>'string'
);
$writer->writeSheetHeader(SHEET_SCRAP, $header);
$writer->writeSheetHeader(SHEET_SETUP, $header);
$writer->writeSheetHeader(SHEET_REWORK, $header);

require 'update/establish.php';

// SELECT TASKS WHICH START DURING THE SHIF
if(strcmp($_GET['shif'], 'day_2')==0){
    $sql_where = "(status_work=3 OR status_work=5) AND (time_start BETWEEN '" .
        $_GET['shif_start'] . ' ' . $_GET['time_start'] . "' AND '" .
        $_GET['shif_end'] . ' ' . $_GET['time_ot_close'] . "')";
    $sql_where_downtime = "status_downtime=3 AND (time_start BETWEEN '" .
        $_GET['shif_start'] . ' ' . $_GET['time_start'] . "' AND '" .
        $_GET['shif_end'] . ' ' . $_GET['time_ot_close'] . "')";
}
$sql_order_by = " ORDER BY planning.id_job, planning.operation, activity.id_machine, activity.time_start";
$sql_order_by_downtime = " ORDER BY planning.id_job, planning.operation, activity_downtime.id_machine, activity_downtime.time_start";

$sql = "SELECT planning.id_task, planning.id_job, planning.operation FROM activity 
    INNER JOIN planning ON activity.id_task=planning.id_task WHERE " . $sql_where . " GROUP BY activity.id_task ORDER BY activity.id_job, activity.operation";
$query_tasks_in_shif = $conn->query($sql);
//echo $sql . "<br><br>";

// SELECT THE FIRST OPERATION OF SUCH TASKS
$sql = "SELECT * FROM planning WHERE first_op=1 AND id_job IN (";
while ($data_tasks_in_shif = $query_tasks_in_shif->fetch_assoc()){
    $sql = $sql . "'" . $data_tasks_in_shif['id_job'] . "',";
}
$sql = rtrim($sql, ',') . ") ORDER BY id_job ASC";
$query_first_op_planning = $conn->query($sql);
//echo $sql . "<br><br>";

$array_space=array('');
$array_tail=array('','','','','','0','','','','','y','','','','','{space}','y','{f4}');

const SHIF_DAY_START='07:00:00';
const SHIF_DAY_CLOSE='15:45:00';
const SHIF_DAY_OT_START='15:45:00';
const SHIF_DAY_OT_CLOSE='19:00:00';
const SHIF_NIGHT_OT_START='19:00:00';
const SHIF_NIGHT_OT_CLOSE='23:00:00';

while ($data_first_op_planning = $query_first_op_planning->fetch_assoc()){
    //SELECT THE FIRST ACTIVITY OF THE FIRST OPERATION
    $sql = "SELECT activity.id_staff, planning.id_job, time_start, id_shif, planning.site, item_no, planning.operation, prod_line, work_center, activity.id_machine,
            no_pulse1, activity.total_work, time_start, time_close, divider.divider AS multiplier FROM activity
            INNER JOIN staff ON activity.id_staff=staff.id_staff
            INNER JOIN planning ON activity.id_task=planning.id_task
            INNER JOIN divider ON (planning.op_color=divider.op_color AND planning.op_side=divider.op_side)
            WHERE activity.id_task=" . $data_first_op_planning['id_task'] . "
            AND time_start = (SELECT MIN(time_start) FROM activity
            WHERE activity.id_task=" . $data_first_op_planning['id_task'] . ")" . $sql_order_by;
//    echo $sql . "<br><br>";
    $query_first_op_activity = $conn->query($sql);
    $data_first_op_activity = $query_first_op_activity->fetch_assoc();

    $date_first_op_from = new DateTime($data_first_op_activity['time_start']);
    $date_first_op_to = new DateTime($data_first_op_activity['time_start']);
    $date_first_op_to->modify('+1 day');

    $sql = "SELECT MAX(time_close) AS max_time_close FROM activity WHERE id_staff='" . $data_first_op_activity['id_staff'] . "' AND (time_close BETWEEN '" .
        $date_first_op_from->format('Y-m-d') . ' ' . SHIF_DAY_START . "' AND '" .
        $date_first_op_to->format('Y-m-d') . ' ' . SHIF_DAY_CLOSE . "')";
    $query_shif_day = $conn->query($sql);
    $data_shif_day = $query_shif_day->fetch_assoc();
    $shif_code = 0;
    if(!empty($data_shif_day)){
        $sql = "SELECT MAX(time_close) AS max_time_close FROM activity WHERE id_staff='" . $data_first_op_activity['id_staff'] . "' AND (time_close BETWEEN '" .
            $date_first_op_from->format('Y-m-d') . ' ' . SHIF_DAY_OT_START . "' AND '" .
            $date_first_op_to->format('Y-m-d') . ' ' . SHIF_DAY_OT_CLOSE . "')";
        $query_shif_day_ot = $conn->query($sql);
        $data_shif_day_ot = $query_shif_day_ot->fetch_assoc();
        if(!empty($data_shif_day_ot)){
            $shif_code = 1; // SHIF DAY WITH OT
        }else{
            $shif_code = 2; // SHIF DAY W/O OT
        }
    }else{
        $sql = "SELECT MAX(time_close) AS max_time_close FROM activity WHERE id_staff='" . $data_first_op_activity['id_staff'] . "' AND (time_close BETWEEN '" .
            $date_first_op_from->format('Y-m-d') . ' ' . SHIF_NIGHT_OT_START . "' AND '" .
            $date_first_op_to->format('Y-m-d') . ' ' . SHIF_NIGHT_OT_CLOSE . "')";
        $query_shif_night_ot = $conn->query($sql);
        $data_shif_night_ot = $query_shif_night_ot->fetch_assoc();
        if(!empty($data_shif_night_ot)){
            $shif_code = 3; // SHIF NIGHT WITH OT
        }else{
            $shif_code = 4; // SHIF NIGHT W/0 OT
        }
    }

    if(!empty($data_first_op_activity)){
        $data_first_op_activity['id_machine'] = make_machine_name($data_first_op_activity['id_machine']);
        $data_first_op_activity['id_shif'] = make_shif_code($data_first_op_activity['id_shif'], $shif_code);
        $data_first_op_activity = make_first_operation_array($data_first_op_activity);
        $data_first_op_activity['time_start'] = date( 'd/m/y', strtotime($data_first_op_activity['time_start']));
        $data_first_op_activity['total_work'] = number_format(time2float($data_first_op_activity['total_work']), 2);
        $data_first_op_activity['no_pulse1']= strval(floor(floatval($data_first_op_activity['no_pulse1'])*floatval($data_first_op_activity['multiplier'])));
        unset($data_first_op_activity['multiplier']);
        $writer->writeSheetRow(SHEET_BF_FIRST, $data_first_op_activity);
    }
}

$sql = "SELECT activity.id_staff, planning.id_job, time_start, id_shif, planning.site, item_no, planning.operation, prod_line, work_center, activity.id_machine,
       no_pulse1, activity.total_work, divider.divider AS multiplier FROM activity
       INNER JOIN staff ON activity.id_staff=staff.id_staff
       INNER JOIN planning ON activity.id_task=planning.id_task
       INNER JOIN divider ON (planning.op_color=divider.op_color AND planning.op_side=divider.op_side)
       WHERE " . $sql_where . $sql_order_by;
$query_current_op_activity = $conn->query($sql);
while ($data_current_op_activity = $query_current_op_activity->fetch_assoc()){

    $date_current_op_from = new DateTime($data_current_op_activity['time_start']);
    $date_current_op_to = new DateTime($data_current_op_activity['time_start']);
    $date_current_op_to->modify('+1 day');

    $sql = "SELECT MAX(time_close) AS max_time_close FROM activity WHERE id_staff='" . $data_current_op_activity['id_staff'] . "' AND (time_close BETWEEN '" .
        $date_current_op_from->format('Y-m-d') . ' ' . SHIF_DAY_START . "' AND '" .
        $date_current_op_to->format('Y-m-d') . ' ' . SHIF_DAY_CLOSE . "')";
    $query_shif_day = $conn->query($sql);
    $data_shif_day = $query_shif_day->fetch_assoc();
    $shif_code = 0;
    if(!empty($data_shif_day)){
        $sql = "SELECT MAX(time_close) AS max_time_close FROM activity WHERE id_staff='" . $data_current_op_activity['id_staff'] . "' AND (time_close BETWEEN '" .
            $date_current_op_from->format('Y-m-d') . ' ' . SHIF_DAY_OT_START . "' AND '" .
            $date_current_op_to->format('Y-m-d') . ' ' . SHIF_DAY_OT_CLOSE . "')";
        $query_shif_day_ot = $conn->query($sql);
        $data_shif_day_ot = $query_shif_day_ot->fetch_assoc();
        if(!empty($data_shif_day_ot)){
            $shif_code = 1; // SHIF DAY WITH OT
        }else{
            $shif_code = 2; // SHIF DAY W/O OT
        }
    }else{
        $sql = "SELECT MAX(time_close) AS max_time_close FROM activity WHERE id_staff='" . $data_current_op_activity['id_staff'] . "' AND (time_close BETWEEN '" .
            $date_current_op_from->format('Y-m-d') . ' ' . SHIF_NIGHT_OT_START . "' AND '" .
            $date_current_op_to->format('Y-m-d') . ' ' . SHIF_NIGHT_OT_CLOSE . "')";
        $query_shif_night_ot = $conn->query($sql);
        $data_shif_night_ot = $query_shif_night_ot->fetch_assoc();
        if(!empty($data_shif_night_ot)){
            $shif_code = 3; // SHIF NIGHT WITH OT
        }else{
            $shif_code = 4; // SHIF NIGHT W/0 OT
        }
    }

    $data_current_op_activity['id_machine'] = make_machine_name($data_current_op_activity['id_machine']);
    $data_current_op_activity['id_shif'] = make_shif_code($data_current_op_activity['id_shif'], $shif_code);
//    $data_current_op_activity['id_shif'] = make_shif_code($data_current_op_activity['id_shif'], $_GET['shif_letter']);
    $data_current_op_activity = make_next_operation_array($data_current_op_activity);
    $data_current_op_activity['time_start'] = date( 'd/m/y', strtotime($data_current_op_activity['time_start']));
    $data_current_op_activity['total_work'] = number_format(time2float($data_current_op_activity['total_work']), 2);
    $data_current_op_activity['no_pulse1']= strval(floor(floatval($data_current_op_activity['no_pulse1'])*floatval($data_current_op_activity['multiplier'])));
    unset($data_current_op_activity['multiplier']);
    $writer->writeSheetRow(SHEET_BF_NEXT, $data_current_op_activity);
}

//$sql = "SELECT activity_downtime.id_staff, planning.id_job, time_start, id_shif, planning.site, item_no, planning.operation, prod_line, work_center, activity_downtime.id_machine,
//       activity_downtime.total_work, code_downtime FROM activity_downtime
//       INNER JOIN staff ON activity_downtime.id_staff=staff.id_staff
//       INNER JOIN planning ON activity_downtime.id_task=planning.id_task
//       INNER JOIN code_downtime ON activity_downtime.id_code_downtime=code_downtime.id_code_downtime
//       WHERE activity_downtime.id_code_downtime <> 'D07' AND " . $sql_where_downtime . $sql_order_by_downtime;
////echo $sql;
//$query_activity_downtime = $conn->query($sql);
//while ($data_activity_downtime = $query_activity_downtime->fetch_assoc()) {
//    $data_activity_downtime['id_machine'] = make_machine_name($data_activity_downtime['id_machine']);
//    $data_activity_downtime['id_shif'] = make_shif_code($data_activity_downtime['id_shif'], $data_activity_downtime['time_start']);
//    $data_activity_downtime = make_downtime_array($data_activity_downtime);
//    $data_activity_downtime['time_start'] = date( 'd/m/y', strtotime($data_activity_downtime['time_start']));
//    $data_activity_downtime['total_work'] = number_format(time2float($data_activity_downtime['total_work']), 2);
//    $writer->writeSheetRow(SHEET_DOWNTIME, $data_activity_downtime);
//}
//
//$sql = "SELECT activity_downtime.id_staff, planning.id_job, time_start, id_shif, planning.site, item_no, planning.operation, prod_line, work_center, activity_downtime.id_machine,
//       activity_downtime.total_work FROM activity_downtime
//       INNER JOIN staff ON activity_downtime.id_staff=staff.id_staff
//       INNER JOIN planning ON activity_downtime.id_task=planning.id_task
//       WHERE activity_downtime.id_code_downtime = 'D07' AND " . $sql_where_downtime . $sql_order_by_downtime;
//$query_activity_setup = $conn->query($sql);
//while ($data_activity_setup = $query_activity_setup->fetch_assoc()) {
//    $data_activity_setup['id_machine'] = make_machine_name($data_activity_setup['id_machine']);
//    $data_activity_setup['id_shif'] = make_shif_code($data_activity_setup['id_shif'], $data_activity_setup['time_start']);
//    $data_activity_setup = make_downtime_array($data_activity_setup);
//    $data_activity_setup['time_start'] = date( 'd/m/y', strtotime($data_activity_setup['time_start']));
//    $data_activity_setup['total_work'] = number_format(time2float($data_activity_setup['total_work']), 2);
//    $writer->writeSheetRow(SHEET_SETUP, $data_activity_setup);
//}

require 'update/terminate.php';

$filename='export';
header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename=$filename.xlsx");
header("Pragma: no-cache");
header("Expires: 0");

$writer->writeToStdOut();

?>