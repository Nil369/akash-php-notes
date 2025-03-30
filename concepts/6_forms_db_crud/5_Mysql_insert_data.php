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


// Adding Data into the table:
$name = readline("Enter your name: ");
$dest = readline("Enter Your destination: ");

// sql query to execute:
$sql = "INSERT INTO trip (`name`, `dest`) VALUES ('$name','$dest')";
$res = mysqli_query($conn,$sql);

// validating successfull data insertion into DB:

if($res){
    echo "<b style='color: green;'>Data inserted into table successfully!</b><br>\n";
}else{
    echo "<p style='color: red;'>Failed to insert data into DB. ERROR --> ". mysqli_connect_error(). "</p><br>\n";
}

?>