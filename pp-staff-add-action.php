<?php

$error_code=0;

require 'update/establish.php';

$sql = "SELECT id_staff FROM staff WHERE (id_staff='" . $_POST['id_staff'] . "' OR id_rfid='" . $_POST['id_rfid'] . "') AND active=1";
$query_staff = $conn->query($sql);
$data_staff = $query_staff->fetch_assoc();

if(empty($data_staff)) {

    // PREPARE FILE INFO BEFORE UPLOAD
    $target_dir = "images/staffs/" . $_POST['id_staff'];
    $extension = pathinfo(basename($_FILES["fileToUpload"]["name"]), PATHINFO_EXTENSION);
    $target_file = $target_dir . "." . $extension;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    $error_code = 0;

    // UPLOAD FILE
    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Error: file already exists. ";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Error: your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "Success: the file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
        } else {
            echo "Error: there was an error uploading your file.";
        }
    }

    if ($uploadOk==1){
        $sql = "INSERT INTO staff (id_staff, id_rfid, prefix, name_first, name_last, id_role, id_shif, staff_img) VALUES ";
        $sql = $sql . "('" . $_POST['id_staff'] . "',";
        $sql = $sql . "'" . $_POST['id_rfid'] . "',";
        $sql = $sql . $_POST['prefix'] . ",";
        $sql = $sql . "'" . $_POST['name_first'] . "',";
        $sql = $sql . "'" . $_POST['name_last'] . "',";
        $sql = $sql . $_POST['id_role'] . ",";
        $sql = $sql . "'" . $_POST['id_shif'] . "',";
        $sql = $sql . "'" . $_POST['id_staff'] . "." . $extension . "')";
//        echo $sql;
        $conn->query($sql);
    }

}else{
    $error_code = 1; // DUPLICATE STAFF ID
}

require 'update/terminate.php';

header("Location: ./pp-staff-add.php?error_code=" . $error_code);
die();

?>