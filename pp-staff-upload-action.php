<?php

ini_set('display_errors', 0);
error_reporting(E_ERROR | E_WARNING | E_PARSE);

$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
$error_code = 0;

// Check if file already exists
if (file_exists($target_file)) {
    echo "Error: file already exists. <br>";
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Error: your file was not uploaded. <br>";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "Success: the file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded. <br>";
    } else {
        echo "Error: there was an error uploading your file. <br>";
    }
}


if (strcmp($imageFileType, "xlsx")==0){
    include 'simplexlsx/src/SimpleXLSX.php';
    $xlsx = new SimpleXLSX($target_file);
} elseif (strcmp($imageFileType, "xls")==0){
    include 'simplexls/src/SimpleXLS.php';
    $xlsx = new SimpleXLS($target_file);
} else{
    $error_code = 1;
}

if($error_code==0){
    define("STAFF_ID", 1);
    define("PREFIX", 2);
    define("FIRST_NAME", 3);
    define("LAST_NAME", 4);
    define("SITE", 5);
    define("ROLE", 6);
    define("SHIF", 8);

    require 'update/establish.php';

    $i = 0;
    foreach ($xlsx->rows() as $p => $fields)
    {
        $i++;

        if ($i<3){
            continue;
        }

        $id_staff = str_pad($fields[STAFF_ID], 4, "0", STR_PAD_LEFT);
        $sql = "SELECT id_staff FROM staff WHERE id_staff='" . $id_staff . "'";
        $query_staff = $conn->query($sql);
        $data_staff = $query_staff->fetch_assoc();

        if (empty($data_staff)){
            $sql = "INSERT INTO staff (id_staff, prefix, name_first, name_last, site, id_role, id_shif) VALUES (";
            $sql = $sql . "'" . $id_staff . "', " . "(SELECT id_prefix FROM prefix WHERE prefix='" . $fields[PREFIX] . "'), '" .
                $fields[FIRST_NAME] . "', '" .
                $fields[LAST_NAME] . "', '" .
                $fields[SITE] . "', " . "(SELECT id_role FROM role WHERE role='" . $fields[ROLE] . "'), '" .
                $fields[SHIF] . "')";
            $conn->query($sql);
            if ($conn->errno){
                $error_code = $conn->errno;
                echo $sql . "<br>";
                break;
            }
        }
    }
    require 'update/terminate.php';
}

header("Location: ./pp-staff-upload.php?error_code=" . $error_code);
die();

?>