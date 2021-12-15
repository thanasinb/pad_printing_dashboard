<?php

// THIS SCRIPT TAKES CARE OF THE MACHINE-LIST BUTTON AND THE ORDERING BUTTON IN THE FIRST COLUMN.

if ($record[QUEUE]>0) {

    // CHECK THIS LOOP SHOULD BE THE NEXT QUEUE (MACHINE) OR NOT?
    // COMPARING BTW THE CURRENT MACHINE ($current_mc) ID AND THE NEW ONE ($record[MACHINE_ID]).
    $current_mc = "";
    if(strcmp($record[MACHINE_ID],$current_mc)!=0){
        $current_mc = $record[MACHINE_ID];
        require 'update/establish.php';
        $sql="SELECT MAX(queue) AS max_queue FROM planning WHERE id_mc='" . $current_mc . "'";
        $result_max_queue = $conn->query($sql);
        require 'update/terminate.php';
        $row_max_queue = $result_max_queue->fetch_assoc();
        $max_queue = $row_max_queue["max_queue"];
    }

    echo "<div class=\"dropdown\">";
//    echo "";
    // SHOW A GREEN BUTTON IF THIS IS THE FIRST QUEUE ($record[QUEUE]=1), SHOW THE GREEN BORDER OTHERWISE
    if ($record[QUEUE] == 1){
//        echo "<i class=\"fas fa-plus-square me-3\"></i>";
        echo "<button class=\"btn-green btn-sm";
    }
    else {
//        echo "<i class=\"fas fa-angle-double-right text-black-50 me-3\"></i>";
        echo "<button class=\"btn-outline-green btn-xs";
    }

    echo " btn fw-bold dropdown-toggle\" style=\"width: 100px;\" id=\"dropdownMenuButton\" ";
    echo "type=\"button\" data-bs-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">";
    echo $record[MACHINE_ID];
//    echo "(" . $row_planning_assigned["queue"] . ")";
    echo "</button>";

    // LIST THE AVAILABLE MACHINE IDs AS DROPDOWN ITEMS
    echo "<div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton\">";
    foreach ($machine_id_mc as $id_mc) {
        if (strcmp($record[MACHINE_ID], $id_mc) != 0) {
            echo "<a class=\"dropdown-item\" ";
            echo "data-id-task=\"" . $record[TASK_ID] . "\" ";
            echo "data-id-mc=\"" . $id_mc . "\" ";                  // new id_mc
            echo "data-id-mc-old=\"" . $record[MACHINE_ID] . "\" "; // current id_mc
            echo "data-queue=\"" . $record[QUEUE] . "\" ";
//            echo "data-row=\"" . $row . "\" ";
            echo ">";
            echo $id_mc;
            echo "</a>";
        }
    }

    // SHOW "Unassign" AS THE LAST DROPDOWN ITEM
    echo "<a class=\"dropdown-item\" ";
    echo "data-id-task=\"" . $record[TASK_ID] . "\" ";
    echo "data-id-mc=\"Unassign\" ";
    echo "data-id-mc-old=\"" . $record[MACHINE_ID] . "\" ";
    echo "data-queue=\"" . $record[QUEUE] . "\" ";
//    echo "data-row=\"" . $row . "\" ";
    echo ">";
    echo "Unassign";
    echo "</a>";
    echo "</div>";

    // SHOW THE BLUE UP-ARROW ONLY IF THIS IS NOT THE FIRST QUEUE
    if($record[QUEUE]!=1){
        echo "<a class=\"pos-up\" ";
        echo "data-id-task=\"" . $record[TASK_ID] . "\" ";
        echo "data-id-mc=\"" . $record[MACHINE_ID] . "\" ";
        echo "data-queue=\"" . $record[QUEUE] . "\" ";
        echo "data-edge=\"" . 0 . "\" ";
//        echo "data-row=\"" . $row . "\" ";
        echo ">";
        echo "<i class=\"fas fa-chevron-circle-up ms-3\"></i></a>";
    }else{
        echo "<a class=\"pos-up\" ";
        echo "data-id-task=\"" . $record[TASK_ID] . "\" ";
        echo "data-id-mc=\"" . $record[MACHINE_ID] . "\" ";
        echo "data-queue=\"" . $record[QUEUE] . "\" ";
        echo "data-edge=\"" . 1 . "\" ";
//        echo "data-row=\"" . $row . "\" ";
        echo ">";
//        echo "<a class=\"text-black-50\">";
        echo "<i class=\"text-black-50 fas fa-chevron-circle-up ms-3\"></i></a>";
    }

    // SHOW THE BLUE DOWN-ARROW ONLY IF THIS IS NOT THE LAST QUEUE
    if($record[QUEUE]!=$max_queue) {
        echo "<a class=\"pos-down\" ";
        echo "data-id-task=\"" . $record[TASK_ID] . "\" ";
        echo "data-id-mc=\"" . $record[MACHINE_ID] . "\" ";
        echo "data-queue=\"" . $record[QUEUE] . "\" ";
        echo "data-edge=\"" . 0 . "\" ";
//        echo "data-row=\"" . $row . "\" ";
        echo ">";
        echo "<i class=\"fas fa-chevron-circle-down ms-2\"></i></a>";
    }else{
        echo "<a class=\"pos-down text-black-50\" ";
        echo "data-id-task=\"" . $record[TASK_ID] . "\" ";
        echo "data-id-mc=\"" . $record[MACHINE_ID] . "\" ";
        echo "data-queue=\"" . $record[QUEUE] . "\" ";
        echo "data-edge=\"" . 1 . "\" ";
//        echo "data-row=\"" . $row . "\" ";
        echo ">";
//        echo "<a class=\"text-black-50\">";
        echo "<i class=\"fas fa-chevron-circle-down ms-2\"></i></a>";
    }
    echo "</div>";
}

// SHOW THE LIST OF AVAILABLE MACHINES FOR THE UNASSIGNED TASKS
else{
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
        echo "data-id-task=\"" . $record[TASK_ID] . "\" ";
        echo "data-id-mc=\"" . $id_mc . "\" ";
        echo "data-id-mc-old=\"0\" ";
        echo "data-queue=\"" . $record[QUEUE] . "\" ";
//        echo "data-row=\"" . $row . "\" ";
        echo ">";
        echo $id_mc;
        echo "</a>";
    }
    echo "</div></div>";
}