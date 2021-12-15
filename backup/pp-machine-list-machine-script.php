<?php
require 'update/establish.php';
$sql = "SELECT * FROM machine";
$result = $conn->query($sql);
while($row = $result->fetch_assoc()){
    echo "<tr>";
    echo "<td>" . "</td>";
    echo "<td>" . $row["id_mc"] . "</td>";
    if ($row["id_workmode"]==2){
        $sql = "SELECT * FROM wip_backflush WHERE id_wip_backflush=" . $row["id_wip"];
        $result_bf = $conn->query($sql);
        $row_bf = $result_bf->fetch_assoc();
        echo "<td>" . $row_bf["id_job"] . "</td>";
        echo "<td>" . $row_bf["id_op"] . "</td>";
        $sql = "SELECT exp_date_finish,exp_time_finish,qty_exp1 FROM operation WHERE ";
        $sql = $sql . "id_job=" . $row_bf["id_job"] . " AND id_op=" . $row_bf["id_op"];
//        echo $sql;
        $result_op = $conn->query($sql);
        $row_op = $result_op->fetch_assoc();
        echo "<td>" . $row_op["exp_date_finish"] . "</td>";
        echo "<td>" . $row_op["exp_time_finish"] . "</td>";
        echo "<td>" . $row_bf["qty_accum"] . "/" . $row_op["qty_exp1"] . "</td>";
        echo "<td><div class=\"progress\"><div class=\"progress-bar\" role=\"progressbar\"";
        echo "style=\"width: 60%\" aria-valuenow=\"";
        $percent = ($row_bf["qty_accum"]/$row_op["qty_exp1"])*100;
        echo $percent;
        echo "\" aria-valuemin=\"0\" aria-valuemax=\"100\">";
        echo $percent;
        echo "%</div></div></td>";
    }else{
        echo "<td>" . "</td>";
        echo "<td>" . "</td>";
        echo "<td>" . "</td>";
        echo "<td>" . "</td>";
        echo "<td>" . "</td>";
        echo "<td>" . "</td>";
    }
    if ($row["id_workmode_next"]==2){
        $sql = "SELECT id_job,id_op FROM wip_backflush WHERE id_wip_backflush=" . $row["id_wip_next"];
        $result_bf = $conn->query($sql);
        $row_bf = $result_bf->fetch_assoc();
        echo "<td>" . $row_bf["id_job"] . "</td>";
        echo "<td>" . $row_bf["id_op"] . "</td>";
    }
    echo "<td>" . "</td>";
    echo "<td>" . "</td>";
    echo "<td><button class=\"btn btn-datatable btn-icon btn-transparent-dark me-2\"><i data-feather=\"edit\"></i></button>";
    echo "<button class=\"btn btn-datatable btn-icon btn-transparent-dark\"><i data-feather=\"trash-2\"></i></button>";
    echo "</td>";
    echo "</tr>";
}
require 'update/terminate.php';
?>