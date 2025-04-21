<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

$data = json_decode(file_get_contents("php://input"), true);
$name = $data['name'] ?? '';


// Search using the params in the url
$search_query = isset($_GET['search']) ? $_GET['search'] : die("Didn't found any Records!");

include "../config/db-connect.php";



$sql = "SELECT * FROM users WHERE name LIKE '%$search_query%'";
$result = mysqli_query($conn, $sql) or die("ERROR::SQL Query Failed!");

if (mysqli_num_rows($result) > 0) {
    $output = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode($output);
} else {
    echo json_encode(array("message" => "No Record Found!", "status" => "fail"));
}

?>