<?php
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

$is_assigned=true;
while($row_planning_assigned = $result_planning_assigned->fetch_assoc()){
    echo "<tr class='text-black fw-bold'>";
//    echo "<td><a href='pp-routecard.php?id_job=" .$row_planning["id_job"]. "'>" . $row_planning["id_job"] . "</a></td>";
//    echo "<td>" . "<button class=\"btn btn-datatable btn-icon btn-transparent-dark me-2\"><i data-feather=\"edit\"></i></button>" . "</td>";
    echo "<td class='align-middle'>";
    require 'pp-planning-list-machine-script.php';
    echo "</td>";
    echo "<td class='align-middle'>" . $row_planning_assigned["id_job"] . "</td>";
    echo "<td class='align-middle'>" . $row_planning_assigned["work_order"] . "</td>";
//    echo "<td class='align-middle'>" . $row_planning["sales_job"] . "</td>";
//    echo "<td class='align-middle'>" . $row_planning["prod_line"] . "</td>";
    echo "<td class='align-middle'>" . $row_planning_assigned["item_no"] . "</td>";
//    echo "<td class='align-middle'>" . $row_planning["item_des"] . "</td>";
//    echo "<td class='align-middle'>" . $row_planning["mold"] . "</td>";
//    echo "<td class='align-middle'>" . $row_planning["site"] . "</td>";
//    echo "<td class='align-middle'>" . $row_planning["type"] . "</td>";
//    echo "<td class='align-middle'>" . $row_planning["work_center"] . "</td>";
    echo "<td class='align-middle'>" . $row_planning_assigned["machine"] . "</td>";
    echo "<td class='align-middle'>" . $row_planning_assigned["operation"] . "</td>";
//    echo "<td class='align-middle'>" . $row_planning["op_des"] . "</td>";
//    echo "<td class='align-middle'>" . number_format($row_planning["qty_order"]) . "</td>";
    echo "<td class='align-middle'>" . number_format($row_planning_assigned["qty_comp"]) . "/" . number_format($row_planning_assigned["qty_order"]);
    $percent = round((intval($row_planning_assigned["qty_comp"]) / intval($row_planning_assigned["qty_order"])) * 100,0);
    echo "<div class=\"progress\">";
    echo "<div class=\"progress-bar\" role=\"progressbar\" style=\"width: " . $percent . "%\" ";
    echo "aria-valuenow=\"" . $percent . "\" aria-valuemin=\"0\" aria-valuemax=\"100\">" . $percent . "%";
    echo "</div></div>";
    echo "</td>";
    echo "<td class='align-middle'>" . number_format($row_planning_assigned["qty_open"]) . "</td>";
    $phpdate = strtotime($row_planning_assigned["date_due"]);
    echo "<td class='align-middle'>" . date( 'd-m-Y', $phpdate ) . "</td>";
//    echo "<td class='align-middle'>" . $row_planning["wo_status"] . "</td>";
    echo "</tr>";
}
$is_assigned=false;
while($row_planning = $result_planning->fetch_assoc()){
    echo "<tr class='text-black fw-bold'>";
//    echo "<td><a href='pp-routecard.php?id_job=" .$row_planning["id_job"]. "'>" . $row_planning["id_job"] . "</a></td>";
//    echo "<td>" . "<button class=\"btn btn-datatable btn-icon btn-transparent-dark me-2\"><i data-feather=\"edit\"></i></button>" . "</td>";
    echo "<td class='align-middle'>";
    require 'pp-planning-list-machine-script.php';
    echo "</td>";
    echo "<td class='align-middle'>" . $row_planning["id_job"] . "</td>";
    echo "<td class='align-middle'>" . $row_planning["work_order"] . "</td>";
//    echo "<td class='align-middle'>" . $row_planning["sales_job"] . "</td>";
//    echo "<td class='align-middle'>" . $row_planning["prod_line"] . "</td>";
    echo "<td class='align-middle'>" . $row_planning["item_no"] . "</td>";
//    echo "<td class='align-middle'>" . $row_planning["item_des"] . "</td>";
//    echo "<td class='align-middle'>" . $row_planning["mold"] . "</td>";
//    echo "<td class='align-middle'>" . $row_planning["site"] . "</td>";
//    echo "<td class='align-middle'>" . $row_planning["type"] . "</td>";
//    echo "<td class='align-middle'>" . $row_planning["work_center"] . "</td>";
    echo "<td class='align-middle'>" . $row_planning["machine"] . "</td>";
    echo "<td class='align-middle'>" . $row_planning["operation"] . "</td>";
//    echo "<td class='align-middle'>" . $row_planning["op_des"] . "</td>";
//    echo "<td class='align-middle'>" . number_format($row_planning["qty_order"]) . "</td>";
    echo "<td class='align-middle'>" . number_format($row_planning["qty_comp"]) . "/" . number_format($row_planning["qty_order"]);
    $percent = round((intval($row_planning["qty_comp"]) / intval($row_planning["qty_order"])) * 100,0);
    echo "<div class=\"progress\">";
    echo "<div class=\"progress-bar\" role=\"progressbar\" style=\"width: " . $percent . "%\" ";
    echo "aria-valuenow=\"" . $percent . "\" aria-valuemin=\"0\" aria-valuemax=\"100\">" . $percent . "%";
    echo "</div></div>";
    echo "</td>";
    echo "<td class='align-middle'>" . number_format($row_planning["qty_open"]) . "</td>";
    $phpdate = strtotime($row_planning["date_due"]);
    echo "<td class='align-middle'>" . date( 'd-m-Y', $phpdate ) . "</td>";
//    echo "<td>" . $row_planning["wo_status"] . "</td>";
    echo "</tr>";
}
?>
