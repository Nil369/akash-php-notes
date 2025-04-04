<?php
// Database Connection
$server = "localhost";
$username = "root";
$password = "";
$database = "devtalks";

$conn = mysqli_connect($server, $username, $password, $database);
if (!$conn) {
    die("Error connecting to database: " . mysqli_connect_error());
}
?>
