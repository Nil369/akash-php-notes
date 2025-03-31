<?php
// Check if user is logged in, if not redirect to login page
function check_login() {
    // Start the session only if it's not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isset($_SESSION['user_id'])) {
        // Get the correct path based on current location
        $base_path = "";
        
        // If we're in a subdirectory of the project, adjust the path
        if (strpos($_SERVER['PHP_SELF'], '/auth/') !== false) {
            $base_path = "";
        } else {
            $base_path = "auth/";
        }
        
        header("Location: {$base_path}login.php");
        exit();
    }
    
    return $_SESSION['user_id'];
}

// Get current user data
function get_user_data($conn, $user_id) {
    $sql = "SELECT id, username, email FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) == 1) {
        return mysqli_fetch_assoc($result);
    }
    
    return null;
}

// Check if username already exists
function username_exists($conn, $username) {
    $sql = "SELECT id FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    
    return mysqli_stmt_num_rows($stmt) > 0;
}

// Check if email already exists
function email_exists($conn, $email) {
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    
    return mysqli_stmt_num_rows($stmt) > 0;
}

// Count user's notes
function count_user_notes($conn, $user_id, $archived = false) {
    $sql = "SELECT COUNT(*) as count FROM notes WHERE user_id = ? AND is_archived = ?";
    $stmt = mysqli_prepare($conn, $sql);
    $archived_value = $archived ? 1 : 0;
    mysqli_stmt_bind_param($stmt, "ii", $user_id, $archived_value);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    
    return $row['count'];
}

// Generate random password
function generate_random_password($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()';
    $password = '';
    $max = strlen($characters) - 1;
    
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[mt_rand(0, $max)];
    }
    
    return $password;
}

// Validate password
function validate_password($password) {
    // At least 6 characters
    if (strlen($password) < 6) {
        return false;
    }
    
    return true;
}

// Clean input data
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?> 