<?php

$servername = "localhost";
$username = "bunnamco_mjrqr";
$password = "6C5uhbaAJsS7mWQ8m6ha";
$dbname = "bunnamco_mjrqr";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("ERROR01" . $conn->connect_error);
}
//echo "Connected successfully";
