<?php
// Start session only if it's not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
require_once "../_db/db_connect.php";
require_once 'auth_functions.php';

$error = '';
$success = '';
$showDemoLink = false; // Control whether to show demo link
$resetLink = '';

// Process forgot password form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    
    if (empty($email)) {
        $error = "Please enter your email";
    } else {
        // Check if email exists
        $sql = "SELECT id, username FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            $user_id = $user['id'];
            
            // Generate a random reset token
            $token = bin2hex(random_bytes(16)); // Use 16 bytes for consistency
            $expiry = date("Y-m-d H:i:s", time() + 86400); // Token expires in 24 hours
            
            // Delete any existing tokens for this user
            $sql = "DELETE FROM password_resets WHERE user_id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "i", $user_id);
            mysqli_stmt_execute($stmt);
            
            // Check if password_resets table exists, create if not
            $check_table = mysqli_query($conn, "SHOW TABLES LIKE 'password_resets'");
            if(mysqli_num_rows($check_table) == 0) {
                $create_table_sql = "CREATE TABLE IF NOT EXISTS password_resets (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    user_id INT NOT NULL,
                    reset_token VARCHAR(255) NOT NULL,
                    expiry_timestamp DATETIME NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
                )";
                mysqli_query($conn, $create_table_sql);
            }
            
            // Store token in password_resets table
            $sql = "INSERT INTO password_resets (user_id, reset_token, expiry_timestamp) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "iss", $user_id, $token, $expiry);
            
            if (mysqli_stmt_execute($stmt)) {
                // Create the reset link
                $resetLink = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/reset-password.php?token=" . urlencode($token);
                
                // Prepare email content
                $to = $email;
                $subject = "Password Reset - iNotes";
                $message = '
                <html>
                <head>
                    <title>Password Reset</title>
                    <style>
                        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
                        .header { background-color: #4e73df; color: white; padding: 15px; text-align: center; border-radius: 5px 5px 0 0; }
                        .content { padding: 20px; }
                        .button { display: inline-block; background-color: #4e73df; color: white; text-decoration: none; padding: 10px 20px; border-radius: 5px; margin: 20px 0; }
                        .footer { font-size: 12px; color: #888; text-align: center; margin-top: 20px; }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <div class="header">
                            <h2>iNotes - Password Reset</h2>
                        </div>
                        <div class="content">
                            <p>Dear ' . htmlspecialchars($user['username']) . ',</p>
                            <p>We received a request to reset your password for your iNotes account. Click the button below to set a new password:</p>
                            <p style="text-align: center;">
                                <a href="' . $resetLink . '" class="button">Reset Password</a>
                            </p>
                            <p>If the button doesn\'t work, you can copy and paste this link into your browser:</p>
                            <p>' . $resetLink . '</p>
                            <p>This link will expire in 24 hours for security reasons.</p>
                            <p>If you didn\'t request a password reset, you can ignore this email and your password will remain unchanged.</p>
                            <p>Thank you,<br>The iNotes Team</p>
                        </div>
                        <div class="footer">
                            This is an automated message, please do not reply to this email.
                        </div>
                    </div>
                </body>
                </html>
                ';
                
                // Set email headers
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= 'From: iNotes <noreply@example.com>' . "\r\n";
                
                // Try to send the email (this will likely fail in local development)
                // Note: for local XAMPP, emails won't actually send unless mail server is configured
                if (@mail($to, $subject, $message, $headers)) {
                    $success = "A password reset link has been sent to your email address. Please check your inbox and spam folder.";
                } else {
                    // If mail function fails, show a message with the demo link
                    $success = "Reset link generated successfully. In a production environment, this would be emailed to you.";
                }
                
                // Always add the demo link in development environment
                $success .= '<div class="demo-link mt-3">' . $resetLink . '</div>';
            } else {
                $error = "Error generating reset token: " . mysqli_error($conn);
            }
        } else {
            $error = "No account found with that email address.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - iNotes</title>
    
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
        
        .forgot-container {
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
            .forgot-container {
                padding: 10px;
            }
            
            .card-body {
                padding: 15px;
            }
        }

        /* Demo Link Style */
        .demo-link {
            word-break: break-all;
            margin-top: 10px;
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }
        
        .alert {
            width: 100%;
            margin-left: 0;
            margin-right: 0;
        }

        [data-bs-theme="dark"] .demo-link {
            background-color: #2c2c2c;
            border-color: #444;
            color: #e0e0e0;
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

    <div class="forgot-container">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-sticky-note me-2"></i>
                <span class="logo-text">iNotes</span>
                <p class="mt-2 mb-0">Your Digital Notebook</p>
            </div>
            <div class="card-body p-4">
                <h4 class="text-center mb-4">Forgot Password</h4>
                
                <?php if(!empty($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                
                <?php if(!empty($success)): ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $success; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted mb-4">Enter your email address and we'll send you a link to reset your password.</p>
                    
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <div class="mb-4">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Send Reset Link
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
                
                <div class="text-center mt-3">
                    <a href="login.php" class="btn btn-link text-decoration-none">
                        <i class="fas fa-arrow-left me-2"></i>Back to Login
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    
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