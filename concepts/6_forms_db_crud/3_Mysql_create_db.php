<?php 

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


// Create a database(db)
$sql = "CREATE DATABASE akash_db";
$result = mysqli_query($conn, $sql);

// Check for the db creation success
if($result){
    echo "<b style='color: green;'>The db was created successfully!</b><br>\n";
}
else{
    echo "<p style='color: red;'>The db was not created successfully because of this error --> ". mysqli_error($conn). "</p><br>\n";
}

?>