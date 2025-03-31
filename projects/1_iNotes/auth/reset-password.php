<?php
// Start session only if it's not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
require_once "../_db/db_connect.php";

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

$error = '';
$success = '';
$email = '';
$email_sent = false;
$reset_token = '';

// Enable this for debugging token issues
$debug_mode = false;

// Process reset token verification
if (isset($_GET['token']) && !empty($_GET['token'])) {
    $reset_token = urldecode($_GET['token']); // Decode the URL-encoded token
    
    // Debug info will be collected and shown in the container, not here
    $debug_info = '';
    if ($debug_mode) {
        $debug_info .= '<div class="token-debug text-center">';
        $debug_info .= '<h6 class="fw-bold mb-3">Token Debug Information</h6>';
        $debug_info .= '<div class="mb-2">';
        $debug_info .= '<strong>Received token:</strong><br>';
        $debug_info .= htmlspecialchars($reset_token);
        $debug_info .= '</div>';
        $debug_info .= '<div>';
        $debug_info .= '<strong>Token length:</strong> ' . strlen($reset_token) . ' characters';
        $debug_info .= '</div>';
        $debug_info .= '</div>';
    }
    
    // Check if token is valid and not expired
    $sql = "SELECT pr.user_id, pr.expiry_timestamp, u.username, u.email 
            FROM password_resets pr
            JOIN users u ON pr.user_id = u.id 
            WHERE pr.reset_token = ? AND pr.expiry_timestamp > NOW()";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $reset_token);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) == 0) {
        // For debugging token issues
        if ($debug_mode) {
            $debug_info .= '<div class="alert alert-warning text-center">';
            $debug_info .= '<strong>Token not found or expired in database!</strong>';
            $debug_info .= '</div>';
            
            // Check if token exists but is expired
            $sql_check = "SELECT reset_token, expiry_timestamp FROM password_resets WHERE reset_token = ?";
            $stmt_check = mysqli_prepare($conn, $sql_check);
            mysqli_stmt_bind_param($stmt_check, "s", $reset_token);
            mysqli_stmt_execute($stmt_check);
            $result_check = mysqli_stmt_get_result($stmt_check);
            
            if (mysqli_num_rows($result_check) > 0) {
                $row_check = mysqli_fetch_assoc($result_check);
                $debug_info .= '<div class="alert alert-info text-center">';
                $debug_info .= '<strong>Token found but expired:</strong><br>';
                $debug_info .= 'Expired: ' . $row_check['expiry_timestamp'] . '<br>';
                $debug_info .= 'Current time: ' . date('Y-m-d H:i:s');
                $debug_info .= '</div>';
            } else {
                $debug_info .= '<div class="alert alert-danger text-center">';
                $debug_info .= '<strong>Token not found in database at all!</strong>';
                $debug_info .= '</div>';
                
                // Show all tokens for debugging
                $all_tokens = mysqli_query($conn, "SELECT reset_token, expiry_timestamp FROM password_resets");
                if (mysqli_num_rows($all_tokens) > 0) {
                    $debug_info .= '<div class="card mb-3">';
                    $debug_info .= '<div class="card-header text-center bg-info text-white">';
                    $debug_info .= '<strong>All tokens in database</strong>';
                    $debug_info .= '</div>';
                    $debug_info .= '<div class="card-body">';
                    $debug_info .= '<ul class="list-group">';
                    while ($token_row = mysqli_fetch_assoc($all_tokens)) {
                        $debug_info .= '<li class="list-group-item">';
                        $debug_info .= '<strong>Token:</strong> ' . htmlspecialchars(substr($token_row['reset_token'], 0, 10)) . '...<br>';
                        $debug_info .= '<strong>Expires:</strong> ' . $token_row['expiry_timestamp'];
                        $debug_info .= '</li>';
                    }
                    $debug_info .= '</ul>';
                    $debug_info .= '</div>';
                    $debug_info .= '</div>';
                } else {
                    $debug_info .= '<div class="alert alert-info text-center">No tokens found in database.</div>';
                }
            }
        }
        
        $error = "Invalid or expired reset token. Please request a new password reset.";
        $reset_token = '';
    } else {
        // Store user info for password reset form
        $user_info = mysqli_fetch_assoc($result);
        
        if ($debug_mode) {
            $debug_info .= '<div class="alert alert-success text-center">';
            $debug_info .= '<strong>Token valid!</strong><br>';
            $debug_info .= 'User: ' . htmlspecialchars($user_info['username']);
            $debug_info .= '</div>';
        }
    }
}

// Process email submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email']) && empty($reset_token)) {
    $email = trim($_POST['email']);
    
    if (empty($email)) {
        $error = "Please enter your email address";
    } else {
        // Check if email exists
        $sql = "SELECT id, username FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) == 0) {
            $error = "No account found with that email address";
        } else {
            $user = mysqli_fetch_assoc($result);
            $user_id = $user['id'];
            $username = $user['username'];
            
            // Generate reset token
            $token = bin2hex(random_bytes(16)); // Use 16 bytes for consistency
            $expiry = date('Y-m-d H:i:s', strtotime('+24 hours'));
            
            // Delete any existing tokens for this user
            $sql = "DELETE FROM password_resets WHERE user_id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "i", $user_id);
            mysqli_stmt_execute($stmt);
            
            // Insert new token
            $sql = "INSERT INTO password_resets (user_id, reset_token, expiry_timestamp) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "iss", $user_id, $token, $expiry);
            
            if (mysqli_stmt_execute($stmt)) {
                // In a real application, you would send an actual email here
                // For this demo, we'll display the reset link directly
                $reset_link = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/reset-password.php?token=" . urlencode($token);
                
                $success = "A password reset link has been created. In a real application, an email would be sent to your address with instructions. <br><br>Reset link: <a href='$reset_link'>$reset_link</a>";
                $email_sent = true;
            } else {
                $error = "Error generating reset token. Please try again later.";
            }
        }
    }
}

// Process password reset
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['password']) && isset($_POST['confirm_password']) && !empty($reset_token)) {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if (empty($password) || empty($confirm_password)) {
        $error = "Both fields are required";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long";
    } else {
        // Get user ID from token
        $sql = "SELECT user_id FROM password_resets WHERE reset_token = ? AND expiry_timestamp > NOW()";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $reset_token);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) == 0) {
            $error = "Invalid or expired reset token. Please request a new password reset.";
        } else {
            $row = mysqli_fetch_assoc($result);
            $user_id = $row['user_id'];
            
            // Update password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET password = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "si", $hashed_password, $user_id);
            
            if (mysqli_stmt_execute($stmt)) {
                // Delete used token
                $sql = "DELETE FROM password_resets WHERE reset_token = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "s", $reset_token);
                mysqli_stmt_execute($stmt);
                
                $success = "Password has been reset successfully! You can now <a href='login.php'>login</a> with your new password.";
                $reset_token = ''; // Clear token to show success message
            } else {
                $error = "Error updating password. Please try again.";
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
    <title>Reset Password - iNotes</title>
    
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
        
        .reset-container {
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
            .reset-container {
                padding: 10px;
            }
            
            .card-body {
                padding: 15px;
            }
        }

        /* Token debug style */
        .token-debug {
            font-family: monospace;
            word-break: break-all;
            background: #f5f5f5;
            padding: 15px;
            border-radius: 8px;
            margin: 0 auto 20px auto;
            max-width: 100%;
            border: 1px solid #ddd;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        [data-bs-theme="dark"] .token-debug {
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

    <div class="reset-container">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-sticky-note me-2"></i>
                <span class="logo-text">iNotes</span>
                <p class="mt-2 mb-0">Your Digital Notebook</p>
            </div>
            <div class="card-body p-4">
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
                
                <?php if ($debug_mode && !empty($reset_token)): ?>
                    <?php echo $debug_info; ?>
                <?php endif; ?>
                
                <?php if(empty($reset_token) && !$email_sent): ?>
                    <h4 class="text-center mb-4">Reset Your Password</h4>
                    <p class="text-muted text-center mb-4">
                        Enter your email address and we'll send you a link to reset your password.
                    </p>
                    
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <div class="mb-4">
                            <label for="email" class="form-label">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email" required value="<?php echo htmlspecialchars($email); ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Send Reset Link
                            </button>
                        </div>
                        <div class="text-center">
                            <p class="mb-0">
                                <a href="login.php" class="text-decoration-none">
                                    <i class="fas fa-arrow-left me-1"></i>Back to Login
                                </a>
                            </p>
                        </div>
                    </form>
                <?php elseif(!empty($reset_token)): ?>
                    <h4 class="text-center mb-4">Create New Password</h4>
                    <p class="text-muted text-center mb-4">
                        Enter your new password below.
                    </p>
                    
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . '?token=' . htmlspecialchars($reset_token)); ?>">
                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
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
                            <label for="confirm_password" class="form-label">Confirm New Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Reset Password
                            </button>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="text-center mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                        <h4 class="mt-3">Check Your Email</h4>
                        <p class="text-muted">
                            We've sent a password reset link to your email. Please check your inbox and follow the instructions.
                        </p>
                        <div class="mt-4">
                            <a href="login.php" class="btn btn-primary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Login
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
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