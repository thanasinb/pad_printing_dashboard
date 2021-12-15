<?php
require '../update/establish.php';

$sql = "DELETE FROM staff WHERE id_staff='" . $_GET['id_staff'] . "'";
$result_staff = $conn->query($sql);

require '../update/terminate.php';

echo json_encode(array("statusCode" => 200), JSON_UNESCAPED_UNICODE);

?>
