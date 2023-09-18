<?php

ini_set('display_errors', 0);
error_reporting(E_ERROR | E_WARNING | E_PARSE);
require '../update/establish.php';
require '../const-status.php';

//$sql = "SELECT
//            machine_queue.id_task,
//            machine_queue.id_staff,
//            planning.id_job,
//            planning.item_no,
//            planning.operation,
//            planning.op_color,
//            planning.op_side,
//            planning.date_due,
//            planning.qty_per_pulse2 AS qty_per_tray,
//            planning.qty_order,
//            planning.qty_comp,
//            planning.qty_open,
//            planning.run_time_std,
//            planning.datetime_update AS last_update,
//            activity.id_activity,
//            activity_rework.id_activity,
//            activity_downtime.id_activity_downtime
//        FROM machine_queue
//        LEFT JOIN planning ON machine_queue.id_task=planning.id_task
//        LEFT JOIN activity ON activity.id_task=planning.id_task AND activity.id_staff=machine_queue.id_staff
//        LEFT JOIN activity_rework ON activity_rework.id_task=planning.id_task AND activity_rework.id_staff=machine_queue.id_staff
//        LEFT JOIN activity_downtime ON activity_downtime.id_task=planning.id_task AND activity_downtime.id_staff=machine_queue.id_staff
//        WHERE machine_queue.queue_number=1 AND machine_queue.id_machine='" . $_GET['id_mc'] . "'
//        AND (activity.status_work<" . STATUS_CLOSED . "
//        OR activity_rework.status_work<" . STATUS_CLOSED . "
//        OR activity_downtime.status_downtime<" . STATUS_CLOSED . ")";

$sql = "SELECT 
            machine_queue.id_task, 
            machine_queue.id_staff,
            planning.id_job,
            planning.item_no, 
            planning.operation, 
            planning.op_color, 
            planning.op_side, 
            planning.date_due, 
            planning.qty_per_pulse2 AS qty_per_tray, 
            planning.qty_order, 
            planning.qty_comp, 
            planning.qty_open, 
            planning.run_time_std,
            planning.datetime_update AS last_update 
        FROM machine_queue 
        LEFT JOIN planning ON machine_queue.id_task=planning.id_task
        WHERE machine_queue.queue_number=1 AND machine_queue.id_machine='" . $_GET['id_mc'] . "'";

$data = $conn->query($sql)->fetch_assoc();
echo json_encode($data);

require '../update/terminate.php';
