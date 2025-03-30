<?php 

// Connecting to Database
$hostname = "localhost";
$username = "root";
$password = "";
$database = "akash_db";


// Creating a connection
$conn = mysqli_connect($hostname, $username, $password, $database);

// validating for successfull connection
if($conn){
    echo "<b style='color: green;'>Connected to Database successfully!</b><br>\n";
}else{
    die("<p style='color: red;'>Sorry we failed to connect to Database: ". mysqli_connect_error(). "</p><br>\n");
}


// Create a table in the db
$sql = "CREATE TABLE `trip` ( `sno` INT(6) NOT NULL AUTO_INCREMENT , `name` VARCHAR(12) NOT NULL , `dest` VARCHAR(6) NOT NULL , PRIMARY KEY (`sno`))";
$result = mysqli_query($conn, $sql);

// Check for the table creation success
if($result){
    echo "<b style='color: green;'>The table was created successfully!</b><br>\n";
}
else{
    echo "<p style='color: red;'>The table was not created successfully because of this error ---> ". mysqli_error($conn). "</p><br>\n";
}

?>