<?php 

// Ways to connect to a MySQL database:
// 1. MySQLi extension -> i) POP  ii) OOP
// 2. PDO -> PHP Data Objects

// Connecting to Database
$hostname = "localhost";
$username = "root";
$password = "";

// Creating a connection
$conn = mysqli_connect($hostname, $username, $password);

// validating for successfull connection
if($conn){
    echo "<b style='color: green;'>Connected to Database successfully!</b><br>\n";
}else{
    die("<p style='color: red;'>Sorry we failed to connect to Database: ". mysqli_connect_error(). "</p><br>\n");
}

?>