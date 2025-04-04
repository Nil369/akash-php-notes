<?php
// Start session
session_start();

// Include database connection
include '_dbConnect.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get signup data from form
    $username = $_POST["username"];
    $email = $_POST["signupEmail"];
    $password = $_POST["signupPassword"];
    $confirmPassword = $_POST["confirmPassword"];
    $agreeTerms = isset($_POST["agreeTerms"]) ? true : false;
    
    // Input validation
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        // Redirect back with error
        header("Location: ../index.php?status=danger&message=Please fill in all fields&username=".$username."&email=".$email);
        exit();
    }
    
    // Check if username is valid
    if (!preg_match("/^[a-zA-Z0-9_]*$/", $username)) {
        header("Location: ../index.php?status=danger&message=Username can only contain letters, numbers, and underscores&email=".$email);
        exit();
    }
    
    // Check if email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../index.php?status=danger&message=Please enter a valid email address&username=".$username);
        exit();
    }
    
    // Check if passwords match
    if ($password !== $confirmPassword) {
        header("Location: ../index.php?status=danger&message=Passwords do not match&username=".$username."&email=".$email);
        exit();
    }
    
    // Check if password meets requirements (at least 8 characters, including numbers and symbols)
    if (strlen($password) < 8 || !preg_match("#[0-9]+#", $password) || !preg_match("#[a-zA-Z]+#", $password)) {
        header("Location: ../index.php?status=danger&message=Password must be at least 8 characters and include letters and numbers&username=".$username."&email=".$email);
        exit();
    }
    
    // Check if terms are agreed
    if (!$agreeTerms) {
        header("Location: ../index.php?status=danger&message=You must agree to the terms and conditions&username=".$username."&email=".$email);
        exit();
    }
    
    // Sanitize inputs
    $username = mysqli_real_escape_string($conn, $username);
    $email = mysqli_real_escape_string($conn, $email);
    
    // Check if username already exists
    $sql = "SELECT username FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        
        if (mysqli_stmt_num_rows($stmt) > 0) {
            header("Location: ../index.php?status=danger&message=Username is already taken&email=".$email);
            exit();
        }
    } else {
        header("Location: ../index.php?status=danger&message=Database error. Please try again later");
        exit();
    }
    
    mysqli_stmt_close($stmt);
    
    // Check if email already exists
    $sql = "SELECT email FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        
        if (mysqli_stmt_num_rows($stmt) > 0) {
            header("Location: ../index.php?status=danger&message=Email is already registered&username=".$username);
            exit();
        }
    } else {
        header("Location: ../index.php?status=danger&message=Database error. Please try again later");
        exit();
    }
    
    mysqli_stmt_close($stmt);
    
    // Insert new user into database
    $sql = "INSERT INTO users (username, email, password, created_at, role) VALUES (?, ?, ?, NOW(), 'Member')";
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt) {
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashedPassword);
        $result = mysqli_stmt_execute($stmt);
        
        if ($result) {
            // User created successfully
            
            // Get the new user ID
            $userId = mysqli_insert_id($conn);
            
            // Create default profile for user
            $sql = "INSERT INTO user_profiles (user_id, bio, avatar) VALUES (?, ?, 'partials/img/avatars/default.png')";
            $profileStmt = mysqli_prepare($conn, $sql);
            $defaultBio = "Hey there! I'm new to DevTalks.";
            $defaultAvatar = 'partials/img/avatars/default.png';
            
            mysqli_stmt_bind_param($profileStmt, "is", $userId, $defaultBio);
            mysqli_stmt_execute($profileStmt);
            
            // Log user in automatically
            $_SESSION["userId"] = $userId;
            $_SESSION["username"] = $username;
            $_SESSION["userEmail"] = $email;
            $_SESSION["userAvatar"] = $defaultAvatar;
            $_SESSION["userRole"] = 'Member';
            
            // Redirect to welcome page
            header("Location: ../index.php?status=success&message=Welcome to DevTalks, $username! Your account has been created successfully.");
            exit();
        } else {
            header("Location: ../index.php?status=danger&message=Error creating account. Please try again later");
            exit();
        }
    } else {
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