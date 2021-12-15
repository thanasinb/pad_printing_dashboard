<?php

const TASK_ID               = 1;
const JOB_ID                = 2;
const WORK_ORDER            = 3;
const SALES_JOB             = 4;
const PRODUCTION_LINE       = 5;
const ITEM_NUMBER           = 6;
const ITEM_DESCRIPTION      = 7;
const MOLD                  = 8;
const SITE_PLANNING         = 9;
const TYPE_PLANNING         = 10;
const WORK_CENTER           = 11;
const MACHINE               = 12;
const OPERATION             = 13;
const OPERATION_DESCRIPTION = 14;
const QTY_ORDER             = 15;
const QTY_COMPLETE          = 16;
const QTY_OPEN              = 17;
const DUE_DATE              = 18;
const WO_STATUS             = 19;
const MACHINE_ID            = 20;
const QUEUE                 = 21;

if(empty($planning)){
    require 'update/establish.php';
    $sql = "SELECT * FROM `planning` WHERE queue = 0 ORDER BY id_task ASC";
    $result_planning = $conn->query($sql);
    $sql = "SELECT * FROM `planning` WHERE queue <> 0 ORDER BY id_mc ASC, queue ASC";
    $result_planning_assigned = $conn->query($sql);
    $sql = "SELECT * FROM machine";
    $result_machine = $conn->query($sql);
    require 'update/terminate.php';

    while($row_machine = $result_machine->fetch_assoc()){
        $machine_id_mc[] = $row_machine["id_mc"];
    }

    $i=0;
    while($row_planning = $result_planning_assigned->fetch_assoc()) {
        $planning[$i][TASK_ID] = $row_planning["id_task"];
        $planning[$i][JOB_ID] = $row_planning["id_job"];
        $planning[$i][WORK_ORDER] = $row_planning["work_order"];
        $planning[$i][SALES_JOB] = $row_planning["sales_job"];
        $planning[$i][PRODUCTION_LINE] = $row_planning["prod_line"];
        $planning[$i][ITEM_NUMBER] = $row_planning["item_no"];
        $planning[$i][ITEM_DESCRIPTION] = $row_planning["item_des"];
        $planning[$i][MOLD] = $row_planning["mold"];
        $planning[$i][SITE_PLANNING] = $row_planning["site"];
        $planning[$i][TYPE_PLANNING] = $row_planning["type"];
        $planning[$i][WORK_CENTER] = $row_planning["work_center"];
        $planning[$i][MACHINE] = $row_planning["machine"];
        $planning[$i][OPERATION] = $row_planning["operation"];
        $planning[$i][OPERATION_DESCRIPTION] = $row_planning["op_des"];
        $planning[$i][QTY_ORDER] = $row_planning["qty_order"];
        $planning[$i][QTY_COMPLETE] = $row_planning["qty_comp"];
        $planning[$i][QTY_OPEN] = $row_planning["qty_open"];
        $planning[$i][DUE_DATE] = $row_planning["date_due"];
        $planning[$i][WO_STATUS] = $row_planning["wo_status"];
        $planning[$i][MACHINE_ID] = $row_planning["id_mc"];
        $planning[$i][QUEUE] = $row_planning["queue"];
        $i++;
    }
    while($row_planning = $result_planning->fetch_assoc()) {
        $planning[$i][TASK_ID] = $row_planning["id_task"];
        $planning[$i][JOB_ID] = $row_planning["id_job"];
        $planning[$i][WORK_ORDER] = $row_planning["work_order"];
        $planning[$i][SALES_JOB] = $row_planning["sales_job"];
        $planning[$i][PRODUCTION_LINE] = $row_planning["prod_line"];
        $planning[$i][ITEM_NUMBER] = $row_planning["item_no"];
        $planning[$i][ITEM_DESCRIPTION] = $row_planning["item_des"];
        $planning[$i][MOLD] = $row_planning["mold"];
        $planning[$i][SITE_PLANNING] = $row_planning["site"];
        $planning[$i][TYPE_PLANNING] = $row_planning["type"];
        $planning[$i][WORK_CENTER] = $row_planning["work_center"];
        $planning[$i][MACHINE] = $row_planning["machine"];
        $planning[$i][OPERATION] = $row_planning["operation"];
        $planning[$i][OPERATION_DESCRIPTION] = $row_planning["op_des"];
        $planning[$i][QTY_ORDER] = $row_planning["qty_order"];
        $planning[$i][QTY_COMPLETE] = $row_planning["qty_comp"];
        $planning[$i][QTY_OPEN] = $row_planning["qty_open"];
        $planning[$i][DUE_DATE] = $row_planning["date_due"];
        $planning[$i][WO_STATUS] = $row_planning["wo_status"];
        $planning[$i][MACHINE_ID] = $row_planning["id_mc"];
        $planning[$i][QUEUE] = $row_planning["queue"];
        $i++;
    }
}

$row=0;
foreach ($planning as $record){
    $record[QUEUE]=strval($record[QUEUE]);
    if ($record[QUEUE] == 1) {
        echo "<tr class=\"text-black fw-bold\" ";
    }else{
        echo "<tr class=\"text-black fw-bold\" ";
    }
    echo "data-id-task=\"" . $record[TASK_ID] . "\" ";
    echo "data-id-mc-old=\"" . $record[MACHINE_ID] . "\" "; // current id_mc
    echo "data-queue=\"" . $record[QUEUE] . "\">";

    echo "<td class='align-middle'>";
    require 'pp-planning-list-machine-script-2.php';
    echo "</td>";
    echo "<td class='align-middle'>" . $record[JOB_ID] . "</td>";
    echo "<td class='align-middle'>" . $record[WORK_ORDER] . "</td>";
//    echo "<td class='align-middle'>" . $record[SALES_JOB] . "</td>";
//    echo "<td class='align-middle'>" . $record[PRODUCTION_LINE] . "</td>";
    echo "<td class='align-middle'>" . $record[ITEM_NUMBER] . "</td>";
//    echo "<td class='align-middle'>" . $record[ITEM_DESCRIPTION] . "</td>";
//    echo "<td class='align-middle'>" . $record[MOLD] . "</td>";
//    echo "<td class='align-middle'>" . $record[SITE_PLANNING] . "</td>";
//    echo "<td class='align-middle'>" . $record[TYPE_PLANNING] . "</td>";
//    echo "<td class='align-middle'>" . $record[WORK_CENTER] . "</td>";
    echo "<td class='align-middle'>" . $record[MACHINE] . "</td>";
    echo "<td class='align-middle'>" . $record[OPERATION] . "</td>";
//    echo "<td class='align-middle'>" . $record[OPERATION_DESCRIPTION] . "</td>";
//    echo "<td class='align-middle'>" . number_format($record[QTY_ORDER]) . "</td>";
    echo "<td class='align-middle'>" . number_format($record[QTY_COMPLETE]) . "/" . number_format($record[QTY_ORDER]);
    $percent = round((intval($record[QTY_COMPLETE]) / intval($record[QTY_ORDER])) * 100,0);
    echo "<div class=\"progress\">";
    echo "<div class=\"progress-bar\" role=\"progressbar\" style=\"width: " . $percent . "%\" ";
    echo "aria-valuenow=\"" . $percent . "\" aria-valuemin=\"0\" aria-valuemax=\"100\">" . $percent . "%";
    echo "</div></div>";
    echo "</td>";
    echo "<td class='align-middle'>" . number_format($record[QTY_OPEN]) . "</td>";
    $phpdate = strtotime($record[DUE_DATE]);
    echo "<td class='align-middle'>" . date( 'd-m-Y', $phpdate ) . "</td>";
//    echo "<td class='align-middle'>" . $record[WO_STATUS] . "</td>";
    echo "</tr>";
    $row++;
//    if ($record[QUEUE] == 2){
//        break;
//    }
}
?>
