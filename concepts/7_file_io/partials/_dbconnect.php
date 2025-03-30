<?php

// Connecting to the Database
$servername = "localhost";
$username = "root";
$password = "";
$database = "akash_db";

// Create a connection
$conn = mysqli_connect($servername, $username, $password, $database);
// Die if connection was not successful
if (!$conn) {
    die("<p style='color:red;'>Sorry we failed to connect: " . mysqli_connect_error() . "</p><br>\n");
} else {
    echo "<b style='color:green;'>Connection was successful!</b><br>";
}

?>