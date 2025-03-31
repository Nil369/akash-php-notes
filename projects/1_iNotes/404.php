<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found | iNotes</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        
        body {
            background-color: var(--bs-body-bg);
            color: var(--bs-body-color);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        
        .error-container {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            text-align: center;
        }
        
        .error-code {
            font-size: 120px;
            font-weight: bold;
            color: #4e73df;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 0;
            line-height: 1;
        }
        
        .error-message {
            font-size: 28px;
            margin-bottom: 20px;
            color: #4e73df;
        }
        
        .error-description {
            margin-bottom: 30px;
            font-size: 18px;
        }
        
        .error-icon {
            font-size: 80px;
            color: #4e73df;
            margin-bottom: 20px;
        }
        
        .btn-primary {
            background-color: #4e73df;
            border-color: #4e73df;
            padding: 10px 20px;
        }
        
        .btn-primary:hover {
            background-color: #3a57c2;
            border-color: #3a57c2;
        }
        
        /* Dark mode styles */
        [data-bs-theme="dark"] {
            --bs-body-bg: #121212;
            --bs-body-color: #e0e0e0;
        }
        
        /* Theme toggle button */
        .theme-toggle {
            position: fixed;
            top: 15px;
            right: 15px;
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            z-index: 1000;
        }
    </style>
</head>
<body>
    <!-- Theme Toggle Button -->
    <div class="theme-toggle btn btn-light" id="themeToggle">
        <i class="fas fa-sun me-1" id="lightIcon"></i>
        <i class="fas fa-moon me-1 d-none" id="darkIcon"></i>
        <span id="themeText">Dark</span>
    </div>

    <div class="error-container">
        <div class="error-icon">
            <i class="fas fa-exclamation-circle"></i>
        </div>
        <h1 class="error-code">404</h1>
        <h2 class="error-message">Page Not Found</h2>
        <p class="error-description">Oops! The page you are looking for doesn't exist or might have been moved.</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="index.php" class="btn btn-primary">
                <i class="fas fa-home me-2"></i>Go to Homepage
            </a>
            <button class="btn btn-outline-primary" onclick="goBack()">
                <i class="fas fa-arrow-left me-2"></i>Go Back
            </button>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    
    <script>
        function goBack() {
            window.history.back();
        }
        
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
                } else {
                    $('#darkIcon').addClass('d-none');
                    $('#lightIcon').removeClass('d-none');
                    $('#themeText').text('Dark');
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
                }
            }
        });
    </script>
</body>
</html> 