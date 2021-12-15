<?php

include_once ("PHP_XLSXWriter/xlsxwriter.class.php");
$header = array(
//    'Task ID'=>'string',
    'ID'=>'string',
    'Work Order'=>'string',
    'Sales/Job'=>'string',
    'Production Line'=>'string',
    'Item Number'=>'string',
    'Description'=>'string',
    'Mold'=>'string',
    'Site'=>'string',
    'Type'=>'string',
    'Work Center'=>'string',
    'Machine'=>'string',
    'Operation'=>'string',
    'Operation Description'=>'string',
    'Quantity Ordered'=>'price',
    'Qty Comp'=>'price',
    'Qty Open'=>'price',
    'Qty process'=>'price',
    'Qty Accum'=>'price',
    'WO Due Date'=>'string',
    'Work Order Status'=>'string',
);
$writer = new XLSXWriter();
$writer->writeSheetHeader('Sheet1', $header);

require 'update/establish.php';
//$sql = "SELECT id_job, work_order, sales_job, prod_line, item_no, item_des, mold, site, type, work_center, machine, operation, op_des, qty_order, qty_comp, qty_open, date_due, wo_status
//FROM planning WHERE status_backup=0 AND id_task IN (SELECT id_task FROM activity WHERE status_work=3)";

$sql = "SELECT CURRENT_TIMESTAMP() AS time_current";
$query_timestamp = $conn->query($sql);

$sql = "SELECT id_job, work_order, sales_job, prod_line, item_no, item_des, mold, site, type, work_center, machine, operation, op_des, qty_order, 
        @qty_comp:=qty_comp as qty_comp, qty_open, 
        @qty_process:=(SELECT SUM(no_pulse1) FROM activity WHERE activity.id_task=planning.id_task AND status_work=3 OR status_work=5) as qty_process, 
        @qty_comp+@qty_process as qty_accum, date_due, wo_status 
        FROM planning WHERE status_backup=0 AND id_task IN (SELECT id_task FROM activity WHERE status_work=3 OR status_work=5)";
$query_select = $conn->query($sql);

$sql = "UPDATE activity SET status_work=5 WHERE status_work=3";
$query_update = $conn->query($sql);
require 'update/terminate.php';

while ($record_update = $query_select->fetch_assoc())
{
    $record_update['date_due'] = date( 'd/m/Y', strtotime($record_update['date_due']));
    $writer->writeSheetRow('Sheet1', $record_update);
}

//$sql = "SELECT id_job, work_order, sales_job, prod_line, item_no, item_des, mold, site, type, work_center, machine, operation, op_des, qty_order, qty_comp, qty_open, date_due, wo_status
//FROM planning WHERE status_backup=0 AND id_task NOT IN (SELECT id_task FROM activity WHERE status_work=3)";
//$query_select = $conn->query($sql);
//require 'update/terminate.php';
//
//while ($record_update = $query_select->fetch_assoc())
//{
//    $writer->writeSheetRow('Sheet1', $record_update);
//}

$record_timestamp = $query_timestamp->fetch_assoc();
//$time_current = strtotime($record_timestamp["time_current"]);
$filename='Printing_export__' . date( 'd-m-Y__H-i-s', strtotime($record_timestamp["time_current"]));
header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename=$filename.xlsx");
header("Pragma: no-cache");
header("Expires: 0");

$writer->writeToStdOut();

$error_code=0;
header("Location: ./pp-export.php?error_code=" . $error_code);
die();

?>