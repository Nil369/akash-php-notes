<?php
// Start session to get error message
session_start();

// Get error message if it exists
$error_message = isset($_SESSION['db_error']) ? $_SESSION['db_error'] : "An unknown database error occurred.";

// We're not using local MySQL anymore, so this check is removed
// $is_mysql_error = strpos($error_message, "MySQL server is not running") !== false;
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Connection Error - iNotes</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 50px;
        }
        .error-container {
            max-width: 600px;
            margin: 0 auto;
        }
        .error-icon {
            font-size: 80px;
            color: #dc3545;
        }
        .steps-container {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
        }
        .step {
            margin-bottom: 15px;
            padding-left: 10px;
            border-left: 3px solid #0d6efd;
        }
        .refresh-btn {
            margin-top: 30px;
        }
        
        /* Theme toggle button */
        .theme-toggle {
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            position: absolute;
            top: 10px;
            right: 10px;
        }
        
        /* Dark mode styles */
        [data-bs-theme="dark"] {
            --bs-body-bg: #121212;
            --bs-body-color: #e0e0e0;
        }
        
        [data-bs-theme="dark"] body {
            background-color: #121212;
        }
        
        [data-bs-theme="dark"] .card {
            background-color: #1e1e1e;
            border-color: #333;
        }
        
        [data-bs-theme="dark"] .steps-container {
            background-color: #1e1e1e;
        }
    </style>
</head>
<body>
    <!-- Theme Toggle Button -->
    <div class="theme-toggle btn btn-outline-dark" id="themeToggle">
        <i class="fas fa-sun me-1" id="lightIcon"></i>
        <i class="fas fa-moon me-1 d-none" id="darkIcon"></i>
        <span id="themeText">Dark</span>
    </div>
    
    <div class="container">
        <div class="error-container">
            <div class="text-center mb-4">
                <i class="fas fa-database error-icon mb-3"></i>
                <h1 class="display-5 fw-bold">Database Connection Error</h1>
                <div class="alert alert-danger mt-3">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            </div>
            
            <div class="card steps-container">
                <h4 class="card-title mb-3"><i class="fas fa-tools me-2"></i>Troubleshooting Steps</h4>
                
                <div class="step">
                    <h5><i class="fas fa-1 me-2"></i>Check Environment Variables</h5>
                    <p>Verify that your database credentials in the .env file are correct and properly loaded.</p>
                </div>
                
                <div class="step">
                    <h5><i class="fas fa-2 me-2"></i>Check Network Connection</h5>
                    <p>Ensure that your application can connect to the cloud database. Check your internet connection and firewall settings.</p>
                </div>
                
                <div class="step">
                    <h5><i class="fas fa-3 me-2"></i>Verify Database Status</h5>
                    <p>Check if your cloud database instance is running and accessible from your current network.</p>
                </div>
            </div>
            
            <div class="d-grid gap-2 refresh-btn">
                <a href="../index.php" class="btn btn-primary btn-lg">
                    <i class="fas fa-sync-alt me-2"></i>Try Again
                </a>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Dark Mode Script -->
    <script>
        $(document).ready(function() {
            // Dark mode toggle functionality
            $('#themeToggle').on('click', function() {
                var htmlElement = document.documentElement;
                var currentTheme = htmlElement.getAttribute('data-bs-theme');
                var newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                
                // Update HTML element
                htmlElement.setAttribute('data-bs-theme', newTheme);
                
                // Update the toggle button appearance
                if (newTheme === 'dark') {
                    $('#lightIcon').addClass('d-none');
                    $('#darkIcon').removeClass('d-none');
                    $('#themeText').text('Light');
                    $('#themeToggle').removeClass('btn-outline-dark').addClass('btn-outline-light');
                } else {
                    $('#darkIcon').addClass('d-none');
                    $('#lightIcon').removeClass('d-none');
                    $('#themeText').text('Dark');
                    $('#themeToggle').removeClass('btn-outline-light').addClass('btn-outline-dark');
                }
                
                // Save preference to localStorage
                localStorage.setItem('theme', newTheme);
            });
            
            // Check for saved theme preference
            var savedTheme = localStorage.getItem('theme');
            if (savedTheme) {
                // Apply saved theme
                document.documentElement.setAttribute('data-bs-theme', savedTheme);
                
                // Update toggle button to match saved theme
                if (savedTheme === 'dark') {
                    $('#lightIcon').addClass('d-none');
                    $('#darkIcon').removeClass('d-none');
                    $('#themeText').text('Light');
                    $('#themeToggle').removeClass('btn-outline-dark').addClass('btn-outline-light');
                }
            }
        });
    </script>
</body>
</html> 