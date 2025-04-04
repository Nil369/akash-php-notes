<?php
// Start session
session_start();

// For testing - uncomment to simulate a logged in user
// $_SESSION["userId"] = 1;
// $_SESSION["username"] = "TestUser";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header Test</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.4/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <div class="container mt-4">
        <h1>Header Test</h1>
        <p>This page tests if the header is correctly showing login/user buttons based on session state.</p>
        
        <h2>Session Information</h2>
        <pre><?php var_dump($_SESSION); ?></pre>
        
        <h2>Header Display</h2>
        <div class="card">
            <div class="card-body">
                <?php include "components/header.php"; ?>
            </div>
        </div>
        
        <h2>Test Controls</h2>
        <div class="btn-group mt-3">
            <a href="header_test.php?action=login" class="btn btn-success">Simulate Login</a>
            <a href="header_test.php?action=logout" class="btn btn-danger">Simulate Logout</a>
        </div>
    </div>
    
    <?php
    // Process test actions
    if (isset($_GET['action'])) {
        if ($_GET['action'] === 'login') {
            $_SESSION['userId'] = 999;
            $_SESSION['username'] = 'TestUser';
            echo '<script>window.location.href = "header_test.php";</script>';
        } elseif ($_GET['action'] === 'logout') {
            session_unset();
            session_destroy();
            echo '<script>window.location.href = "header_test.php";</script>';
        }
    }
    ?>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.4/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 