<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

$data = json_decode(file_get_contents("php://input"), true);
$name = $data['name'] ?? '';
$email = $data['email'] ?? '';

include "../config/db-connect.php";

if (!empty($name) && !empty($email)) {

    $sql = "INSERT INTO users (name, email, created_at) VALUES ('$name', '$email', NOW())";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(["message" => "User Created Successfully!", "status" => "success!"]);
    } else {
        echo json_encode(["message" => "Failed to Create User", "status" => "failed!"]);
    }

} else {
    echo json_encode(["message" => "Missing name or email", "status" => false]);
}

?>