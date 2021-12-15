<?php
require 'update/establish.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sql = "SELECT id_mc FROM machine WHERE id_mc='" . $_POST["id_mc"] . "'";
    $result = $conn->query($sql);
    if($result->num_rows == 0) {
        $sql = "INSERT INTO machine (";
        $sql = $sql . "id_mc,";
        $sql = $sql . "id_mc_type,";
        $sql = $sql . "mc_des";
        $sql = $sql . ") VALUES (";
        $sql = $sql . "'" . $_POST["id_mc"] . "',";
        $sql = $sql . "" . $_POST["id_mc_type"] . ",";
        $sql = $sql . "'" . $_POST["mc_des"] . "')";
//        echo $sql;
        $conn->query($sql);
    }
}
require 'update/terminate.php';
?>