<?php
require 'establish.php';
$dt = new DateTime('NOW');
$dt_text = $dt->format('Y-m-d H:i:s');

$sql = "INSERT INTO log (log_datetime, log_description) VALUES ('" . $dt_text . "', '". $_GET['log']. "')";
$conn->query($sql);
echo $sql;
require 'terminate.php';

//require 'url.php';
//$url = URL_EXTERNAL . 'update/log-bunnam.php?dt=' . $dt_text . '&log=' . $_GET['log'];
//echo $url . "<br>";

//$ch = curl_init();
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($ch, CURLOPT_URL, $url);
//$result=curl_exec($ch);
//curl_close($ch);
//var_dump($result);

//$result = file_get_contents($url);

//var_dump(json_decode($result, true));
//var_dump(json_decode($result, true));
//header('Location: ' . $url . 'update/log-bunnam.php?dt=' . $dt_text . '&log=' . $_GET['log']);
//die();

