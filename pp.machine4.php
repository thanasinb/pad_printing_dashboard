<?php
require 'update/establish.php';// เชื่อมต่อฐานข้อมูล

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_mc = $_POST['id_mc'];
    $id_mc_type = $_POST['id_mc_type'];
    $mc_des = $_POST['mc_des'];

    // เพิ่มข้อมูลลงในฐานข้อมูล
    $sql = "INSERT INTO machines (id_mc, id_mc_type, mc_des) VALUES ('$id_mc', '$id_mc_type', '$mc_des')";
    if (mysqli_query($conn, $sql)) {
        // หากเพิ่มข้อมูลสำเร็จ ส่งกลับหน้าหลักพร้อมพารามิเตอร์ success
        header("Location: pp-machine-add.php?success=1");
        exit();
    } else {
        // หากเกิดข้อผิดพลาด
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>
