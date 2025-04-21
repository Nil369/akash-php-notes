<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

$data = json_decode(file_get_contents("php://input"), true);

$uid = $data['id'] ?? '';
$name = $data['name'] ?? '';
$email = $data['email'] ?? '';

include "../config/db-connect.php";

if (!empty($uid) && !empty($name) && !empty($email)) {

    $sql = "UPDATE users SET name='$name', email='$email' WHERE id=$uid";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(["message" => "User Updated Successfully!", "status" => "success!"]);
    } else {
        echo json_encode(["message" => "Failed to Update User", "status" => "fail!"]);
    }

} else {
    echo json_encode(["message" => "Missing fields", "status" => false]);
}

?>