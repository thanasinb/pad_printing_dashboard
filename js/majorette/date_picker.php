<?php
require 'update/establish.php';
$sql = "SELECT * FROM machine_queue WHERE queue_number=1 ORDER BY id_machine ASC ";
$result_machine_queue = $conn->query($sql);
echo "<script>\n";
$i=0;
echo "var dateNow = new Date();";
while ($row_machine_queue = $result_machine_queue->fetch_assoc()){
    echo "var complete_date_picker_" . $i . " = function () {\n";
    echo "var complete_date_" . $i . " = function () {\n";
    echo "$('#datetimepicker_date_" . $i . "').datetimepicker({\n";
    echo "format: 'DD-MM-YYYY',\n";
    if(strcmp($row_machine_queue["comp_date"], "0000-00-00")!=0){
        echo "defaultDate: new Date('" . date("m/d/Y", strtotime($row_machine_queue["comp_date"])) . "'),\n";
    }
    echo "useCurrent: false\n";
    echo "});\n";
//    echo "$('#datetimepicker_date_" . $i . "').on('change.datetimepicker', function () {\n";
//    echo "alert($('#input_date_" . $i . "').val());\n";
//    echo "});\n";
    echo "}\n";
    echo "return {\n";
    echo "init: function() {\n";
    echo "complete_date_" . $i . "();\n";
    echo "}\n";
    echo "};\n";
    echo "}();\n";
    echo "var complete_time_picker_" . $i . " = function () {\n";
    echo "var complete_time_" . $i . " = function () {\n";
    echo "$('#datetimepicker_time_" . $i . "').datetimepicker({\n";
    echo "format: 'HH:mm',\n";
    if(strcmp($row_machine_queue["comp_time"], "00:00:00")!=0){
        echo "defaultDate: moment(dateNow).hours(" . date("H", strtotime($row_machine_queue["comp_time"])) . ").minutes(" . date("i", strtotime($row_machine_queue["comp_time"])) . ").seconds(0).milliseconds(0),\n";
    }
    echo "useCurrent: false\n";
    echo "});\n";
//    echo "$('#datetimepicker_time_" . $i . "').on('change.datetimepicker', function () {\n";
//    echo "alert($('#input_time_" . $i . "').val());\n";
//    echo "});\n";
    echo "}\n";
    echo "return {\n";
    echo "init: function() {\n";
    echo "complete_time_" . $i . "();\n";
    echo "}\n";
    echo "};\n";
    echo "}();\n";
    $i++;
}

$sql = "SELECT * FROM machine_queue WHERE queue_number=1 ORDER BY id_machine ASC ";
$result_machine_queue = $conn->query($sql);
echo "jQuery(document).ready(function() {\n";
$i=0;
while ($row_machine_queue = $result_machine_queue->fetch_assoc()){
    echo "complete_date_picker_" . $i . ".init();\n";
    echo "complete_time_picker_" . $i . ".init();\n";
    $i++;
}

echo "});\n";
echo "</script>\n";
require 'update/terminate.php';
