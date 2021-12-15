<?php
if ($_GET['id_mc']!=null){
    $sql = "UPDATE machine SET ";
    $sql = $sql . "time_contact=CURRENT_TIMESTAMP()";
    $sql = $sql . " WHERE id_mc='" . $_GET["id_mc"] . "'";
    $result = $conn->query($sql);
} elseif($_GET['id_machine']!=null){
    $sql = "UPDATE machine SET ";
    $sql = $sql . "time_contact=CURRENT_TIMESTAMP()";
    $sql = $sql . " WHERE id_mc='" . $_GET["id_machine"] . "'";
    $result = $conn->query($sql);
}
?>