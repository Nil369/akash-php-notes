<?php
// Include authentication functions
require_once "auth/auth_functions.php";

// Check if user is logged in
$user_id = check_login();

require_once "_db/db_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $tag = !empty($_POST['tag']) ? trim($_POST['tag']) : 'General';
    $is_pinned = isset($_POST['is_pinned']) ? 1 : 0;
    
    // Use prepared statement to prevent SQL injection
    $sql = "INSERT INTO notes (user_id, title, description, tag, is_pinned) 
            VALUES (?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "isssi", $user_id, $title, $description, $tag, $is_pinned);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?> 