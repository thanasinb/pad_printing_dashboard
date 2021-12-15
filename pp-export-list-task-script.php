<?php
require 'update/establish.php';

$sql = "SELECT DISTINCT id_job FROM activity WHERE (status_work=3 OR status_work=5) AND time_start BETWEEN '2021-09-06 07:00:00' AND '2021-09-06 15:00:00'";



//$sql = "SELECT id_job, work_order, sales_job, prod_line, item_no, item_des, mold, site, type, work_center, machine, operation, op_des, qty_order,
//        @qty_comp:=qty_comp as qty_comp, qty_open,
//        @qty_process:=(SELECT SUM(no_pulse1) FROM activity WHERE activity.id_task=planning.id_task AND status_work=3 OR status_work=5) as qty_process,
//        @qty_comp+@qty_process as qty_accum, date_due, wo_status
//        FROM planning WHERE status_backup=0 AND id_task IN (SELECT id_task FROM activity WHERE status_work=3 OR status_work=5)";
//$query_planning = $conn->query($sql);
//while($record_planning = $query_planning->fetch_assoc()) {
//    echo "<tr class=\"text-black fw-bold\">";
//    $i=0;
//    foreach ($record_planning as $field_planning) {
//        if($i>12 && $i<18){
//            echo "<td>" . number_format($field_planning) . "</td>";
//        }
//        elseif ($i==18){
//            echo "<td>" . date( 'd-m-Y', strtotime($field_planning)) . "</td>";
//        }
//        else{
//            echo "<td>" . $field_planning . "</td>";
//        }
//        $i++;
//    }
//    echo "</tr>";
//}

//$sql = "SELECT id_job, work_order, sales_job, prod_line, item_no, item_des, mold, site, type, work_center, machine, operation, op_des, qty_order, qty_comp, qty_open, date_due, wo_status
//FROM planning WHERE status_backup=0 AND id_task NOT IN (SELECT id_task FROM activity WHERE status_work=3 OR status_work=5)";
//$query_planning = $conn->query($sql);
//while($record_planning = $query_planning->fetch_assoc()) {
//    echo "<tr class=\"text-black fw-bold\">";
//    foreach ($record_planning as $field_planning) {
//        echo "<td>" . $field_planning . "</td>";
//    }
//    echo "</tr>";
//}

require 'update/terminate.php';
?>