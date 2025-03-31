<?php
// Start session only if it's not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

// Include database connection
require_once "../_db/db_connect.php";

$error = '';
$success = '';

// Process registration form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Basic validation
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long";
    } else {
        // Check if username already exists
        $sql = "SELECT id FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $error = "Username already exists";
        } else {
            // Check if email already exists
            $sql = "SELECT id FROM users WHERE email = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            
            if (mysqli_stmt_num_rows($stmt) > 0) {
                $error = "Email already exists";
            } else {
                // All checks passed, insert new user
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashed_password);
                
                if (mysqli_stmt_execute($stmt)) {
                    $success = "Registration successful! You can now log in.";
                    // Redirect to login page after 2 seconds
                    header("Refresh: 2; URL=login.php");
                } else {
                    $error = "Error: " . mysqli_error($conn);
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - iNotes</title>
    
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
        
        .register-container {
            width: 100%;
            max-width: 450px;
            margin: 0 auto;
        }
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,.05);
            transition: all 0.3s;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,.1);
        }
        
        .card-header {
            background-color: #4e73df;
            color: white;
            text-align: center;
            border-radius: 10px 10px 0 0 !important;
            padding: 25px;
        }
        
        .btn-primary {
            background-color: #4e73df;
            border-color: #4e73df;
            width: 100%;
            padding: 10px;
        }
        
        .btn-primary:hover {
            background-color: #3a57c2;
            border-color: #3a57c2;
        }
        
        .logo-text {
            font-size: 24px;
            font-weight: bold;
        }
        
        /* Password strength indicator */
        .password-strength {
            height: 5px;
            margin-top: 5px;
            border-radius: 5px;
            transition: all 0.3s;
        }
        
        /* Dark mode styles */
        [data-bs-theme="dark"] {
            --bs-body-bg: #121212;
            --bs-body-color: #e0e0e0;
        }
        
        [data-bs-theme="dark"] .card {
            background-color: #1e1e1e;
            border-color: #333;
        }
        
        [data-bs-theme="dark"] .form-control {
            background-color: #2c2c2c;
            border-color: #444;
            color: #e0e0e0;
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
        
        /* Responsive adjustments */
        @media (max-width: 480px) {
            .register-container {
                padding: 10px;
            }
            
            .card-body {
                padding: 15px;
            }
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

    <div class="register-container">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-sticky-note me-2"></i>
                <span class="logo-text">iNotes</span>
                <p class="mt-2 mb-0">Your Digital Notebook</p>
            </div>
            <div class="card-body p-4">
                <h4 class="text-center mb-4">Create an Account</h4>
                
                <?php if(!empty($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                
                <?php if(!empty($success)): ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $success; ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="registerForm">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="password-strength" id="passwordStrength"></div>
                        <small id="passwordHelpBlock" class="form-text text-muted">
                            Password must be at least 6 characters long.
                        </small>
                    </div>
                    <div class="mb-4">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-user-plus me-2"></i>Register
                        </button>
                    </div>
                    <div class="text-center">
                        <p class="mb-0">Already have an account? <a href="login.php" class="text-decoration-none">Login</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Password visibility toggle
            $('#togglePassword').on('click', function() {
                const passwordField = $('#password');
                const passwordFieldType = passwordField.attr('type');
                const toggleIcon = $(this).find('i');
                
                if (passwordFieldType === 'password') {
                    passwordField.attr('type', 'text');
                    toggleIcon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordField.attr('type', 'password');
                    toggleIcon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
            
            // Password strength indicator
            $('#password').on('input', function() {
                const password = $(this).val();
                const strengthBar = $('#passwordStrength');
                let strength = 0;
                
                if (password.length > 0) {
                    // Length check
                    if (password.length >= 6) strength += 1;
                    if (password.length >= 10) strength += 1;
                    
                    // Character variety checks
                    if (/[A-Z]/.test(password)) strength += 1;
                    if (/[0-9]/.test(password)) strength += 1;
                    if (/[^A-Za-z0-9]/.test(password)) strength += 1;
                    
                    // Update strength bar
                    switch(strength) {
                        case 0:
                        case 1:
                            strengthBar.css('width', '20%').css('background-color', '#dc3545'); // Weak
                            break;
                        case 2:
                        case 3:
                            strengthBar.css('width', '60%').css('background-color', '#ffc107'); // Medium
                            break;
                        case 4:
                        case 5:
                            strengthBar.css('width', '100%').css('background-color', '#198754'); // Strong
                            break;
                    }
                } else {
                    strengthBar.css('width', '0%');
                }
            });
            
            // Form validation
            $('#registerForm').on('submit', function(e) {
                const password = $('#password').val();
                const confirmPassword = $('#confirm_password').val();
                
                if (password !== confirmPassword) {
                    e.preventDefault();
                    alert('Passwords do not match');
                }
            });
            
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