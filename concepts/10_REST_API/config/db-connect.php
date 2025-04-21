<?php

$hostname = "localhost";
$username = "root";
$password = "";
$db_name = "rest-crud";

$conn = mysqli_connect($hostname,$username, $password,$db_name)
        or die("Connection Failed");

?>