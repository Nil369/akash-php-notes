<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

$data = json_decode(file_get_contents("php://input"), true);

$uid = $data['id'] ?? '';


include "../config/db-connect.php";

if (!empty($uid)) {

    $sql = "DELETE FROM users WHERE id=$uid";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(["message" => "User Deleted Successfully!", "status" => "success!"]);
    } else {
        echo json_encode(["message" => "Failed to Delete User", "status" => "failed!"]);
    }

} else {
    echo json_encode(["message" => "Missing user ID", "status" => false]);
}

?>