<?php

$servername = "localhost";
$username = "bunnamco_majoretteppmc";
$password = "G8zEkJsiRJz4t8c";
$dbname = "bunnamco_majorettepp";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("ERROR01" . $conn->connect_error);
}
//echo "Connected successfully";
