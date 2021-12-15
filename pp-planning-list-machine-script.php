<?php
if ($is_assigned) {

    $current_mc = "";
    if(strcmp($row_planning_assigned["id_mc"],$current_mc)!=0){
        $current_mc = $row_planning_assigned["id_mc"];
        require 'update/establish.php';
        $sql="SELECT MAX(queue) AS max_queue FROM planning WHERE id_mc='" . $current_mc . "'";
        $result_max_queue = $conn->query($sql);
        require 'update/terminate.php';
        $row_max_queue = $result_max_queue->fetch_assoc();
        $max_queue = $row_max_queue["max_queue"];
    }

    echo "<div class=\"dropdown\">";
    echo "<button class=\"";
    if ($row_planning_assigned["queue"] == 1)
        echo "btn-green btn-sm";
    else
        echo "btn-outline-green btn-xs";
    echo " btn fw-bold dropdown-toggle\" style=\"width: 100px;\" id=\"dropdownMenuButton\" ";
    echo "type=\"button\" data-bs-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">";
    echo $row_planning_assigned["id_mc"];
//    echo "(" . $row_planning_assigned["queue"] . ")";
    echo "</button>";
    echo "<div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton\">";
    foreach ($machine_id_mc as $id_mc) {
        if (strcmp($row_planning_assigned["id_mc"], $id_mc) != 0) {
            echo "<a class=\"dropdown-item\" ";
            echo "data-id-task=\"" . $row_planning_assigned["id_task"] . "\" ";
            echo "data-id-mc=\"" . $id_mc . "\" ";
            echo "data-id-mc-old=\"" . $row_planning_assigned["id_mc"] . "\" ";
            echo "data-queue=\"" . $row_planning_assigned["queue"] . "\" ";
            echo ">";
            echo $id_mc;
            echo "</a>";
        }
    }
    echo "<a class=\"dropdown-item\" ";
    echo "data-id-task=\"" . $row_planning_assigned["id_task"] . "\" ";
    echo "data-id-mc=\"Unassign\" ";
    echo "data-id-mc-old=\"" . $row_planning_assigned["id_mc"] . "\" ";
    echo "data-queue=\"" . $row_planning_assigned["queue"] . "\" ";
    echo ">";
    echo "Unassign";
    echo "</a>";
    echo "</div>";

    if($row_planning_assigned["queue"]!=1){
        echo "<a class=\"pos-up\" ";
        echo "data-id-task=\"" . $row_planning_assigned["id_task"] . "\" ";
        echo "data-id-mc=\"" . $row_planning_assigned["id_mc"] . "\" ";
        echo "data-queue=\"" . $row_planning_assigned["queue"] . "\" ";
        echo ">";
        echo "<i class=\"fas fa-chevron-circle-up ms-3\"></i></a>";
    }else{
        echo "<a class=\"text-black-50\">";
        echo "<i class=\"fas fa-chevron-circle-up ms-3\"></i></a>";
    }

    if($row_planning_assigned["queue"]!=$max_queue) {
        echo "<a class=\"pos-down\" ";
        echo "data-id-task=\"" . $row_planning_assigned["id_task"] . "\" ";
        echo "data-id-mc=\"" . $row_planning_assigned["id_mc"] . "\" ";
        echo "data-queue=\"" . $row_planning_assigned["queue"] . "\" ";
        echo ">";
        echo "<i class=\"fas fa-chevron-circle-down ms-2\"></i></a>";
    }else{
        echo "<a class=\"text-black-50\">";
        echo "<i class=\"fas fa-chevron-circle-down ms-2\"></i></a>";
    }

    echo "</div>";
}else{
    echo "<div id=\"id_task\"></div>";
    echo "<div id=\"id_mc\"></div>";
    echo "<div class=\"dropdown\">";
    echo "<button class=\"";
    echo "btn-light btn-sm btn fw-bold dropdown-toggle\" style=\"width: 100px;\" id=\"dropdownMenuButton\" ";
    echo "type=\"button\" data-bs-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">";
    echo "Select MC";
    echo "</button>";
    echo "<div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton\">";
    foreach ($machine_id_mc as $id_mc) {
        echo "<a class=\"dropdown-item\" ";
        echo "data-id-task=\"" . $row_planning["id_task"] . "\" ";
        echo "data-id-mc=\"" . $id_mc . "\" ";
        echo "data-id-mc-old=\"0\" ";
        echo "data-queue=\"" . $row_planning["queue"] . "\" ";
        echo ">";
        echo $id_mc;
        echo "</a>";
    }
    echo "</div></div>";
}