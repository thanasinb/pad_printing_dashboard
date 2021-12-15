<?php
require 'update/establish.php';
$sql = "SELECT * FROM log ORDER BY id_log DESC";
//echo $sql;
$result_log = $conn->query($sql);
require 'update/terminate.php';

echo '<html><body><table border="1">';
while($data_log = $result_log->fetch_assoc()){
    echo '<tr><td>' . $data_log['log_datetime'] . '</td><td>' . $data_log['log_description'] . '</td></tr>';
}
echo '</table></body></html>';
