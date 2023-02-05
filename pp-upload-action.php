<?php

ini_set('display_errors', 0);
error_reporting(E_ERROR | E_WARNING | E_PARSE);

$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
$error_code = 0;
$msg = "Upload success";

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
    $msg = "Error code: 1";
}

const FIELD_ID_JOB=0;
const FIELD_OPERATION=11;
const FIELD_FIRST_OP=12;
const FIELD_OP_DESCRIPTION=13;
const FIELD_QTY_ORDER=16;
const FIELD_QTY_COMP=17;
const FIELD_QTY_OPEN=18;

if($error_code==0){
    require 'update/establish.php';
    require 'update/lib_add_log.php';

    $sql = "INSERT INTO planning (";
    $sql = $sql . "id_job, work_order, sales_job, prod_line, item_no, item_des, mold, site, type, ";
    $sql = $sql . "work_center, machine, operation, first_op, op_des, op_side, op_color, run_time_std, run_open_total, qty_order, qty_comp, qty_open, date_due, wo_status, datetime_update, qty_per_pulse2";
    $sql = $sql . ") ";
    $sql = $sql . "VALUES (";

    $list_planning = "(";

    foreach ($xlsx->rows() as $p => $fields)
    {
        $id_job = $fields[FIELD_ID_JOB];
        $operation = $fields[FIELD_OPERATION];
        $first_op = $fields[FIELD_FIRST_OP];
        $op_description = $fields[FIELD_OP_DESCRIPTION];
        $qty_order = $fields[FIELD_QTY_ORDER];
        $qty_comp = $fields[FIELD_QTY_COMP];
        $qty_open = $fields[FIELD_QTY_OPEN];
//        $op_description = preg_replace('/[^A-Za-z0-9\-]/', '', $op_description);
//        echo $op_description . "<br>";
//        echo substr($op_description, 0, 3) . "<br>";
        $offset=-2;
        $op_side = substr($op_description, $offset, 1);
//        echo $op_side . "<br>";
//        echo strpos($op_description,"TOP") . "<br>";
        if (strcmp($op_side,"X")==0) {
            $offset--;
            $op_side = substr($op_description, $offset, 1);
//            echo "hello0<br>";
            if (strcmp(($op_side), " ") == 0) {
                $offset--;
                $op_side = substr($op_description, $offset, 1);
            } elseif (strcmp(($op_side), ".") == 0) {
                $offset--;
                $op_side = substr($op_description, $offset, 1);
            }
            if (strcmp(($op_side), "5") == 0) {
                $offset = $offset - 2;
                $op_side = substr($op_description, $offset, 3);
            } elseif (strcmp(($op_side), "L") == 0) {
                $offset--;
                $op_side = substr($op_description, $offset, 1);

            } elseif (strcmp(($op_side), "R") == 0) {
                $offset--;
                $op_side = substr($op_description, $offset, 1);
            }
            $offset = $offset--;
            $op_color = substr($op_description, $offset, 1);
            while (!is_numeric($op_color) and $offset > -20) {
                $offset--;
                $op_color = substr($op_description, $offset, 1);
            }
        }elseif (strpos($op_description, "TOP")) {
            $op_side = "B";
            $op_color = "1";
//            echo "hello1<br>";
        }
//        elseif (strpos($op_description, "LIGHT")){
//            $op_side = "B";
//            $op_color = "1";
//            echo "hello1<br>";
//        }
        elseif (strpos($op_description,"MIRROR")){
            $op_side="B";
            $op_color="1";
//            echo "hello1<br>";
        }else{
//            echo "hello3<br>";
            continue;
        }

        $stmt = $sql;
        if ($p == 0) continue;
        for ($item=0; $item<12; $item++){
            $stmt = $stmt . "'" . mysqli_real_escape_string($conn ,$fields[$item]) . "', ";
        }
        if (strcmp($first_op, 'Yes')==0){
            $stmt = $stmt . " 1, ";
        }else{
            $stmt = $stmt . " 0, ";
        }
        $stmt = $stmt . "'" . mysqli_real_escape_string($conn ,$op_description) . "', ";
        $stmt = $stmt . "'" . $op_side . "', ";
        $stmt = $stmt . "'" . $op_color . "', ";
        for ($item=14; $item<19; $item++){
            $stmt = $stmt . $fields[$item] . ",";
        }
        $stmt = $stmt . "'" . substr($fields[$item++],0,-9) . "',";
        $stmt = $stmt . "'" . $fields[$item] . "', '" . date('Y-m-d H:i:s') . "',";

        $stmt = $stmt . "80" . ")";

        $list_planning = $list_planning . "(" . $id_job . "," . $operation . "),";

        $sql_select = "SELECT id_task FROM planning WHERE id_job='" . $id_job . "' AND operation = '" . $operation . "'";
//        echo $sql_select . "<br>";
//        echo $stmt . "<br>";
//        echo $op_description . "," . $op_color . "," . $op_side . "<br>";

        $query_planning = $conn->query($sql_select);
        $data_planning = $query_planning->fetch_assoc();

        // IF THE RECORD DOES NOT EXIST, ADD THE NEW ONE
        if (empty($data_planning)){
            $conn->query($stmt);
            if ($conn->errno){
                $error_code = $conn->errno;
                add_log($conn, $stmt);
                break;
            }
        }

        // IF THE RECORD EXISTS, UPDATE QTY_COMP, QTY_OPEN
        else{
            $sql_update = "UPDATE planning SET ";
            $sql_update = $sql_update . "qty_order=" . $qty_order . ", ";
            $sql_update = $sql_update . "qty_comp=" . $qty_comp . ", ";
            $sql_update = $sql_update . "qty_open=" . $qty_open . ", ";
            $sql_update = $sql_update . "datetime_update='" . date('Y-m-d H:i:s') . "' ";
            $sql_update = $sql_update . "WHERE id_task=" . $data_planning['id_task'];
            $conn->query($sql_update);
            if ($conn->errno){
                $error_code = $conn->errno;
                add_log($conn, $sql_update);
                break;
            }

            $sql_update = "UPDATE activity SET status_work=6 WHERE status_work=5 AND id_task=" . $data_planning['id_task'];
            $conn->query($sql_update);
            if ($conn->errno){
                $error_code = $conn->errno;
                add_log($conn, $sql_update);
                break;
            }
        }
    }

    $list_planning = rtrim($list_planning, ",");
    $list_planning = $list_planning . ")";

    $sql_backup = "UPDATE activity INNER JOIN planning ON activity.id_task=planning.id_task SET activity.status_work=6 WHERE activity.status_work=5 AND (planning.id_job, planning.operation) NOT IN " . $list_planning;
//    echo $sql_backup . "<br>";
    $conn->query($sql_backup);
    if ($conn->errno){
        $error_code = $conn->errno;
        add_log($conn, $sql_backup);
    }

    $sql_backup = "UPDATE planning SET status_backup=1 WHERE (id_job, operation) NOT IN " . $list_planning;
    $conn->query($sql_backup);
    if ($conn->errno){
        $error_code = $conn->errno;
        add_log($conn, $sql_backup);
    }

    require 'update/terminate.php';
}

header("Location: ./pp-upload.php?error_code=" . $error_code);
die();

?>