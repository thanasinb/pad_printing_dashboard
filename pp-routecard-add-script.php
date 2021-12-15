<?php
require 'update/establish.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sql = "SELECT id_job FROM job WHERE ";
    $sql = $sql . "id_job='" . $_POST["id_job"] . "'";
    $result = $conn->query($sql);
    if($result->num_rows == 0) {
        $sql = "INSERT INTO job (";
        $sql = $sql . "id_job,";
        $sql = $sql . "work_order,";
        $sql = $sql . "item_no,";
        $sql = $sql . "item_des,";
        $sql = $sql . "sales_job,";
        $sql = $sql . "qty_order,";
        $sql = $sql . "qty_completed,";
        $sql = $sql . "qty_rejected,";
        $sql = $sql . "date_order_job,";
        $sql = $sql . "date_release_job,";
        $sql = $sql . "date_due_job,";
        $sql = $sql . "prod_line,";
        $sql = $sql . "prod_rate";
        $sql = $sql . ") VALUES (";
        $sql = $sql . "" . $_POST["id_job"] . ",";
        $sql = $sql . "'" . $_POST["work_order"] . "',";
        $sql = $sql . "'" . $_POST["item_no"] . "',";
        $sql = $sql . "'" . $_POST["item_des"] . "',";
        $sql = $sql . "'" . $_POST["sales_job"] . "',";
        $sql = $sql . "" . $_POST["qty_order"] . ",";
        $sql = $sql . "" . $_POST["qty_completed"] . ",";
        $sql = $sql . "" . $_POST["qty_rejected"] . ",";
        $sql = $sql . "'" . $_POST["date_order_job"] . "',";
        $sql = $sql . "'" . $_POST["date_release_job"] . "',";
        $sql = $sql . "'" . $_POST["date_due_job"] . "',";
        $sql = $sql . "'" . $_POST["prod_line"] . "',";
        $sql = $sql . "" . $_POST["prod_rate"] . ")";
//    echo $sql;
        $conn->query($sql);
    }
}
require 'update/terminate.php';
?>