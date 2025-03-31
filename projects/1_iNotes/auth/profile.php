<?php
// Start session only if it's not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "auth_functions.php";
require_once "../_db/db_connect.php";

// Check if user is logged in
$user_id = check_login();
$user = get_user_data($conn, $user_id);

$error = '';
$success = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Update profile
    if (isset($_POST['update_profile'])) {
        $username = clean_input($_POST['username']);
        $email = clean_input($_POST['email']);
        
        // Validate username and email
        if (empty($username) || empty($email)) {
            $error = "Username and email are required";
        } elseif ($username !== $user['username'] && username_exists($conn, $username)) {
            $error = "Username already exists";
        } elseif ($email !== $user['email'] && email_exists($conn, $email)) {
            $error = "Email already exists";
        } else {
            // Update user profile
            $sql = "UPDATE users SET username = ?, email = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssi", $username, $email, $user_id);
            
            if (mysqli_stmt_execute($stmt)) {
                // Update session variables
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;
                
                $success = "Profile updated successfully";
                $user['username'] = $username;
                $user['email'] = $email;
            } else {
                $error = "Error updating profile: " . mysqli_error($conn);
            }
        }
    }
    
    // Change password
    if (isset($_POST['change_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        // Verify current password
        $sql = "SELECT password FROM users WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user_data = mysqli_fetch_assoc($result);
        
        if (!password_verify($current_password, $user_data['password'])) {
            $error = "Current password is incorrect";
        } elseif (empty($new_password) || empty($confirm_password)) {
            $error = "New password and confirmation are required";
        } elseif ($new_password !== $confirm_password) {
            $error = "New passwords do not match";
        } elseif (!validate_password($new_password)) {
            $error = "Password must be at least 6 characters long";
        } else {
            // Update password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET password = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "si", $hashed_password, $user_id);
            
            if (mysqli_stmt_execute($stmt)) {
                $success = "Password changed successfully";
            } else {
                $error = "Error changing password: " . mysqli_error($conn);
            }
        }
    }
}

// Get stats
$total_notes = count_user_notes($conn, $user_id, false);
$archived_notes = count_user_notes($conn, $user_id, true);
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - iNotes</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .user-avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid #4e73df;
        }
        
        .stats-card {
            transition: transform 0.3s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
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
        
        /* Password strength indicator */
        .password-strength {
            height: 5px;
            margin-top: 5px;
            border-radius: 5px;
            transition: all 0.3s;
        }
        
        /* Theme toggle button */
        .theme-toggle {
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            margin-left: 10px;
        }
        
        @media (max-width: 768px) {
            .user-avatar {
                width: 100px;
                height: 100px;
            }
            
            .profile-header {
                text-align: center;
            }
        }
        
        body {
            background-color: var(--bs-body-bg);
            color: var(--bs-body-color);
            min-height: 100vh;
            padding-bottom: 20px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="../index.php">
            <i class="fas fa-sticky-note me-2"></i>iNotes
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="../index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../index.php?view=archived">Archived</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle active" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-1"></i> <?php echo htmlspecialchars($user['username']); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item active" href="profile.php"><i class="fas fa-id-card me-2"></i>Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </li>
                <!-- Theme Toggle Button -->
                <li class="nav-item">
                    <div class="theme-toggle btn btn-outline-light" id="themeToggle">
                        <i class="fas fa-sun me-1" id="lightIcon"></i>
                        <i class="fas fa-moon me-1 d-none" id="darkIcon"></i>
                        <span id="themeText">Dark</span>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="container my-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2><i class="fas fa-user me-2"></i>User Profile</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
                    <li class="breadcrumb-item active">Profile</li>
                </ol>
            </nav>
        </div>
    </div>

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

    <!-- Profile Header -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row profile-header">
                <div class="col-md-3 text-center mb-3 mb-md-0">
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($user['username']); ?>&background=4e73df&color=fff&size=150" class="user-avatar" alt="User Avatar">
                </div>
                <div class="col-md-9">
                    <h3><?php echo htmlspecialchars($user['username']); ?></h3>
                    <p class="text-muted"><i class="fas fa-envelope me-2"></i><?php echo htmlspecialchars($user['email']); ?></p>
                    <p class="text-muted"><i class="fas fa-calendar-alt me-2"></i>Member since: 
                        <?php 
                            $sql = "SELECT DATE_FORMAT(created_at, '%M %d, %Y') as joined_date FROM users WHERE id = ?";
                            $stmt = mysqli_prepare($conn, $sql);
                            mysqli_stmt_bind_param($stmt, "i", $user_id);
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);
                            $date_row = mysqli_fetch_assoc($result);
                            echo $date_row['joined_date'];
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3 mb-md-0">
            <div class="card stats-card border-left-primary h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col-auto">
                            <i class="fas fa-sticky-note fa-2x text-primary"></i>
                        </div>
                        <div class="col ml-3">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Active Notes</div>
                            <div class="h5 mb-0 font-weight-bold"><?php echo $total_notes; ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3 mb-md-0">
            <div class="card stats-card border-left-success h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col-auto">
                            <i class="fas fa-archive fa-2x text-success"></i>
                        </div>
                        <div class="col ml-3">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Archived Notes</div>
                            <div class="h5 mb-0 font-weight-bold"><?php echo $archived_notes; ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stats-card border-left-info h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col-auto">
                            <i class="fas fa-tags fa-2x text-info"></i>
                        </div>
                        <div class="col ml-3">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Tags Used</div>
                            <div class="h5 mb-0 font-weight-bold">
                                <?php 
                                    $sql = "SELECT COUNT(DISTINCT tag) as tag_count FROM notes WHERE user_id = ?";
                                    $stmt = mysqli_prepare($conn, $sql);
                                    mysqli_stmt_bind_param($stmt, "i", $user_id);
                                    mysqli_stmt_execute($stmt);
                                    $result = mysqli_stmt_get_result($stmt);
                                    $tag_row = mysqli_fetch_assoc($result);
                                    echo $tag_row['tag_count'];
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs for Profile Settings -->
    <div class="card shadow-sm">
        <div class="card-body">
            <ul class="nav nav-tabs" id="profileTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="edit-profile-tab" data-bs-toggle="tab" data-bs-target="#edit-profile" type="button" role="tab">
                        <i class="fas fa-user-edit me-2"></i>Edit Profile
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="change-password-tab" data-bs-toggle="tab" data-bs-target="#change-password" type="button" role="tab">
                        <i class="fas fa-key me-2"></i>Change Password
                    </button>
                </li>
            </ul>
            <div class="tab-content p-4" id="profileTabsContent">
                <!-- Edit Profile Tab -->
                <div class="tab-pane fade show active" id="edit-profile" role="tabpanel">
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>
                        <button type="submit" name="update_profile" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Save Changes
                        </button>
                    </form>
                </div>
                
                <!-- Change Password Tab -->
                <div class="tab-pane fade" id="change-password" role="tabpanel">
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="passwordForm">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                            <div class="password-strength" id="passwordStrength"></div>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                        <button type="submit" name="change_password" class="btn btn-primary">
                            <i class="fas fa-key me-2"></i>Change Password
                        </button>
                    </form>
                </div>
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
        // Password strength indicator
        $('#new_password').on('input', function() {
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
        
        // Password confirmation validation
        $('#passwordForm').on('submit', function(e) {
            const newPassword = $('#new_password').val();
            const confirmPassword = $('#confirm_password').val();
            
            if (newPassword !== confirmPassword) {
                e.preventDefault();
                alert('New passwords do not match');
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