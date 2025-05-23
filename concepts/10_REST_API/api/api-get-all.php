<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

include "../config/db-connect.php";

$sql = "SELECT * FROM users";
$result = mysqli_query($conn, $sql) or die("ERROR::SQL Query Failed!");

if (mysqli_num_rows($result) > 0) {
    $output = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode($output);
} else {
    echo json_encode(array("message" => "No Record Found!", "status" => "fail"));
}
