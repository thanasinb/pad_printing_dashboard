<?php

require 'quit_v3.php';
require 'establish.php';
require 'lib_get_active_activity_by_machine.php';
require 'lib_add_activity_downtime.php';

$table_downtime = 'activity_downtime';
$str_downtime = 'status_downtime';
$data_activity_downtime = get_active_activity_by_machine($conn, $table_downtime, $str_downtime, $_GET["id_mc"]);

// INSERT INTO THE TABLE ONLY IF THERE IS NO SUCH ACTIVITY_DOWNTIME (NEW)
if(empty($data_activity_downtime)) {
    $data_json = add_activity_downtime(
        $conn,
        $table_downtime,
        $data_activity['id_task'],
        $_GET["id_mc"],
        $data_activity['id_staff'],
        $data_activity['shif'],
        $data_activity['date_eff'],
        $_GET["code_downtime"]);
}else{
    $data_json = json_encode(array("code" => "021", "message" => "Downtime is already registered by staff ID: " . $data_activity_downtime['id_staff']), JSON_UNESCAPED_UNICODE);
}
print_r($data_json);

require 'terminate.php';
?>