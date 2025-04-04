<?php
// Start session
session_start();

// Include database connection
include '_dbConnect.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get login credentials from form
    $email = $_POST["loginEmail"];
    $password = $_POST["loginPassword"];
    $rememberMe = isset($_POST["rememberMe"]) ? true : false;
    
    // Input validation
    if (empty($email) || empty($password)) {
        // Redirect back with error
        header("Location: ../index.php?status=danger&message=Please fill in all fields");
        exit();
    }
    
    // Sanitize inputs
    $email = mysqli_real_escape_string($conn, $email);
    
    // Check if user exists in database
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($row = mysqli_fetch_assoc($result)) {
            // Verify password
            $passwordCheck = password_verify($password, $row["password"]);
            
            if ($passwordCheck) {
                // Password is correct, start session
                $_SESSION["userId"] = $row["id"];
                $_SESSION["username"] = $row["username"];
                $_SESSION["userRole"] = $row["role"];
                
                // Store user's email and avatar if available
                if (isset($row["email"])) {
                    $_SESSION["userEmail"] = $row["email"];
                }
                
                if (isset($row["avatar"])) {
                    $_SESSION["userAvatar"] = $row["avatar"];
                }
                
                // Set remember me cookie if checked
                if ($rememberMe) {
                    $selector = bin2hex(random_bytes(8));
                    $token = random_bytes(32);
                    
                    $expires = date("U") + 60 * 60 * 24 * 30; // 30 days
                    
                    // Delete any existing token
                    $sql = "DELETE FROM auth_tokens WHERE user_id = ?";
                    $stmt = mysqli_prepare($conn, $sql);
                    mysqli_stmt_bind_param($stmt, "i", $row["id"]);
                    mysqli_stmt_execute($stmt);
                    
                    // Insert new token
                    $sql = "INSERT INTO auth_tokens (user_id, selector, token, expires) VALUES (?, ?, ?, ?)";
                    $stmt = mysqli_prepare($conn, $sql);
                    $hashedToken = password_hash($token, PASSWORD_DEFAULT);
                    mysqli_stmt_bind_param($stmt, "isss", $row["id"], $selector, $hashedToken, $expires);
                    mysqli_stmt_execute($stmt);
                    
                    // Set cookie
                    setcookie("rememberme", $selector . ":" . bin2hex($token), time() + 60 * 60 * 24 * 30, "/", "", false, true);
                }
                
                // Update last login time
                $sql = "UPDATE users SET last_login = NOW() WHERE id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "i", $row["id"]);
                mysqli_stmt_execute($stmt);
                
                // Redirect to home page or requested page
                if (isset($_SESSION["redirectUrl"])) {
                    $redirectUrl = $_SESSION["redirectUrl"];
                    unset($_SESSION["redirectUrl"]);
                    header("Location: $redirectUrl?status=success&message=Welcome back, " . htmlspecialchars($row["username"]) . "!");
                } else {
                    header("Location: ../index.php?status=success&message=Welcome back, " . htmlspecialchars($row["username"]) . "!");
                }
                exit();
            } else {
                // Wrong password
                header("Location: ../index.php?status=danger&message=Incorrect password");
                exit();
            }
        } else {
            // User doesn't exist
            header("Location: ../index.php?status=danger&message=No account found with that email");
            exit();
        }
    } else {
        // SQL statement preparation failed
        header("Location: ../index.php?status=danger&message=Database error. Please try again later");
        exit();
    }
    
    mysqli_stmt_close($stmt);
} else {
    // Not a POST request, redirect to home
    header("Location: ../index.php");
    exit();
}

mysqli_close($conn);
?> 