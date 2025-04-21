<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$data = json_decode(file_get_contents("php://input"), true);
// $user_id = $data['uid'];

// GET user by using the params in the url
$user_id = isset($_GET['uid']) ? $_GET['uid'] : die("Failed to find user id!");

include "../config/db-connect.php";



$sql = "SELECT * FROM users WHERE id={$user_id}";
$result = mysqli_query($conn, $sql) or die("ERROR::SQL Query Failed!");

if (mysqli_num_rows($result) > 0) {
    $output = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode($output);
} else {
    echo json_encode(array("message" => "No Record Found!", "status" => "fail"));
}

?>