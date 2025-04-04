<?php
// Start session
session_start();

// Include auth helper and require login
include 'partials/auth_helper.php';
requireLogin();

// Include database connection
include 'partials/_dbConnect.php';

// Get user data from session or database
$userData = [
    'id' => $_SESSION['userId'],
    'username' => $_SESSION['username'],
    'email' => isset($_SESSION['userEmail']) ? $_SESSION['userEmail'] : 'user@example.com',
    'joinDate' => '2023-01-15',
    'bio' => isset($_SESSION['userBio']) ? $_SESSION['userBio'] : 'Full-stack developer passionate about web technologies and open source.',
    'location' => isset($_SESSION['userLocation']) ? $_SESSION['userLocation'] : 'San Francisco, CA',
    'website' => isset($_SESSION['userWebsite']) ? $_SESSION['userWebsite'] : 'https://example.com',
    'github' => isset($_SESSION['userGithub']) ? $_SESSION['userGithub'] : 'github_username',
    'twitter' => isset($_SESSION['userTwitter']) ? $_SESSION['userTwitter'] : 'twitter_username',
    'linkedin' => isset($_SESSION['userLinkedin']) ? $_SESSION['userLinkedin'] : 'linkedin_username',
    'avatar' => isset($_SESSION['userAvatar']) ? $_SESSION['userAvatar'] : 'partials/img/avatars/default.png'
];

// Process form submissions
$saveSuccess = false;
$saveError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['profile_submit'])) {
        // Handle profile image upload
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
            $allowed = array('jpg', 'jpeg', 'png', 'gif');
            $filename = $_FILES['avatar']['name'];
            $fileExt = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            // Check if file extension is allowed
            if (in_array($fileExt, $allowed)) {
                // Create unique filename
                $newFilename = uniqid('avatar_') . '.' . $fileExt;
                $uploadDir = 'partials/img/avatars/';
                
                // Create upload directory if it doesn't exist
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $uploadPath = $uploadDir . $newFilename;
                
                // Move uploaded file
                if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadPath)) {
                    // Update session data
                    $_SESSION['userAvatar'] = $uploadPath;
                    $userData['avatar'] = $uploadPath;
                    
                    // Update the database with new avatar
                    $sql = "UPDATE user_profiles SET avatar = ? WHERE user_id = ?";
                    $stmt = mysqli_prepare($conn, $sql);
                    mysqli_stmt_bind_param($stmt, "si", $uploadPath, $_SESSION['userId']);
                    mysqli_stmt_execute($stmt);
                } else {
                    $saveError = 'Failed to upload image. Please try again.';
                }
            } else {
                $saveError = 'Invalid file format. Allowed formats: jpg, jpeg, png, gif';
            }
        }
        
        // Update other profile data
        $_SESSION['username'] = $_POST['username'];
        $_SESSION['userEmail'] = $_POST['email'];
        $_SESSION['userBio'] = $_POST['bio'];
        $_SESSION['userLocation'] = $_POST['location'];
        $_SESSION['userWebsite'] = $_POST['website'];
        $_SESSION['userGithub'] = $_POST['github'];
        $_SESSION['userTwitter'] = $_POST['twitter'];
        $_SESSION['userLinkedin'] = $_POST['linkedin'];
        
        // Update userData for the current page
        $userData['username'] = $_POST['username'];
        $userData['email'] = $_POST['email'];
        $userData['bio'] = $_POST['bio'];
        $userData['location'] = $_POST['location'];
        $userData['website'] = $_POST['website'];
        $userData['github'] = $_POST['github'];
        $userData['twitter'] = $_POST['twitter'];
        $userData['linkedin'] = $_POST['linkedin'];
        
        // Update the database with new profile info
        $sql = "UPDATE users SET username = ?, email = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssi", $_POST['username'], $_POST['email'], $_SESSION['userId']);
        mysqli_stmt_execute($stmt);
        
        $sql = "UPDATE user_profiles SET bio = ?, location = ?, website = ?, github = ?, twitter = ?, linkedin = ? WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssssi", $_POST['bio'], $_POST['location'], $_POST['website'], $_POST['github'], $_POST['twitter'], $_POST['linkedin'], $_SESSION['userId']);
        mysqli_stmt_execute($stmt);
        
        $saveSuccess = true;
    } else if (isset($_POST['password_submit'])) {
        // Handle password update
        // In a real application, you would validate and update the password
        $saveSuccess = true;
    } else if (isset($_POST['preferences_submit'])) {
        // Handle preferences update
        // In a real application, you would update preferences
        $saveSuccess = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Dev Talks</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.4/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-DQvkBjpPgn7RC31MCQoOeC9TI2kdqa4+BSgNMNj8v77fdC77Kj5zpWFTJaaAoMbC" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="partials/css/style.css">
    <!-- Set initial theme based on user preference -->
    <script>
        // Check for saved theme preference
        const savedTheme = localStorage.getItem('theme') || 
                        (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        
        // Apply theme class to document
        document.documentElement.setAttribute('data-bs-theme', savedTheme);
        document.write(`<body class="${savedTheme}-mode">`);
    </script>
</head>
<body>

    <?php 
        include "components/header.php"; 
        include "components/loginModal.php";
        include "components/signupModal.php";
    ?>

    <div class="container mt-4">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 mb-4">
                <div class="card sticky-top" style="top: 20px;">
                    <div class="card-header">
                        <h4 class="mb-0">Settings</h4>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="#profile" class="list-group-item list-group-item-action active">
                            <i class="bi bi-person me-2"></i> Profile
                        </a>
                        <a href="#account" class="list-group-item list-group-item-action">
                            <i class="bi bi-shield-lock me-2"></i> Account
                        </a>
                        <a href="#preferences" class="list-group-item list-group-item-action">
                            <i class="bi bi-sliders me-2"></i> Preferences
                        </a>
                        <a href="#notifications" class="list-group-item list-group-item-action">
                            <i class="bi bi-bell me-2"></i> Notifications
                        </a>
                        <a href="#privacy" class="list-group-item list-group-item-action">
                            <i class="bi bi-lock me-2"></i> Privacy
                        </a>
                        <a href="dashboard.php" class="list-group-item list-group-item-action">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-lg-9">
                <!-- Success/Error Messages -->
                <?php if ($saveSuccess): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i> Your settings have been saved successfully.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>
                
                <?php if ($saveError): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i> <?php echo $saveError; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>
                
                <!-- Profile Settings -->
                <section id="profile" class="mb-5">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="mb-0">Profile Settings</h3>
                        </div>
                        <div class="card-body">
                            <form action="settings.php" method="post" enctype="multipart/form-data">
                                <!-- Avatar -->
                                <div class="mb-4 text-center">
                                    <img src="<?php echo $userData['avatar']; ?>" class="avatar avatar-lg mb-3" style="width: 150px; height: 150px; object-fit: cover;" alt="User avatar">
                                    <div>
                                        <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*" style="max-width: 300px; margin: 0 auto;">
                                        <small class="text-muted">Maximum file size: 2MB. Recommended size: 200x200 pixels.</small>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <!-- Username -->
                                    <div class="col-md-6 mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" class="form-control" id="username" name="username" value="<?php echo $userData['username']; ?>" required>
                                    </div>
                                    
                                    <!-- Email -->
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $userData['email']; ?>" required>
                                    </div>
                                </div>
                                
                                <!-- Bio -->
                                <div class="mb-3">
                                    <label for="bio" class="form-label">Bio</label>
                                    <textarea class="form-control" id="bio" name="bio" rows="3"><?php echo $userData['bio']; ?></textarea>
                                    <small class="text-muted">Brief description about yourself. Maximum 200 characters.</small>
                                </div>
                                
                                <div class="row">
                                    <!-- Location -->
                                    <div class="col-md-6 mb-3">
                                        <label for="location" class="form-label">Location</label>
                                        <input type="text" class="form-control" id="location" name="location" value="<?php echo $userData['location']; ?>">
                                    </div>
                                    
                                    <!-- Website -->
                                    <div class="col-md-6 mb-3">
                                        <label for="website" class="form-label">Website</label>
                                        <input type="url" class="form-control" id="website" name="website" value="<?php echo $userData['website']; ?>">
                                    </div>
                                </div>
                                
                                <!-- Social Links -->
                                <h5 class="mt-4 mb-3">Social Media</h5>
                                <div class="row">
                                    <!-- GitHub -->
                                    <div class="col-md-4 mb-3">
                                        <label for="github" class="form-label">
                                            <i class="bi bi-github me-1"></i> GitHub
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">@</span>
                                            <input type="text" class="form-control" id="github" name="github" value="<?php echo $userData['github']; ?>">
                                        </div>
                                    </div>
                                    
                                    <!-- Twitter -->
                                    <div class="col-md-4 mb-3">
                                        <label for="twitter" class="form-label">
                                            <i class="bi bi-twitter me-1"></i> Twitter
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">@</span>
                                            <input type="text" class="form-control" id="twitter" name="twitter" value="<?php echo $userData['twitter']; ?>">
                                        </div>
                                    </div>
                                    
                                    <!-- LinkedIn -->
                                    <div class="col-md-4 mb-3">
                                        <label for="linkedin" class="form-label">
                                            <i class="bi bi-linkedin me-1"></i> LinkedIn
                                        </label>
                                        <input type="text" class="form-control" id="linkedin" name="linkedin" value="<?php echo $userData['linkedin']; ?>">
                                    </div>
                                </div>
                                
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary" name="profile_submit">Save Profile</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
                
                <!-- Account Settings -->
                <section id="account" class="mb-5">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="mb-0">Account Settings</h3>
                        </div>
                        <div class="card-body">
                            <form action="settings.php" method="post">
                                <h5 class="mb-3">Change Password</h5>
                                
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Current Password</label>
                                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="new_password" class="form-label">New Password</label>
                                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                                    <small class="text-muted">Password must be at least 8 characters long and contain letters, numbers, and special characters.</small>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                </div>
                                
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary" name="password_submit">Update Password</button>
                                </div>
                            </form>
                            
                            <hr class="my-4">
                            
                            <h5 class="mb-3 text-danger">Danger Zone</h5>
                            <div class="mb-3">
                                <p>Deactivating your account will temporarily hide your profile and content. You can reactivate at any time.</p>
                                <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#deactivateModal">Deactivate Account</button>
                            </div>
                            
                            <div class="mb-3">
                                <p>Deleting your account is permanent. All your data, posts and comments will be permanently removed.</p>
                                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">Delete Account</button>
                            </div>
                        </div>
                    </div>
                </section>
                
                <!-- Preferences Settings -->
                <section id="preferences" class="mb-5">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="mb-0">Preferences</h3>
                        </div>
                        <div class="card-body">
                            <form action="settings.php" method="post">
                                <h5 class="mb-3">Display</h5>
                                
                                <div class="mb-3">
                                    <label class="form-label">Theme</label>
                                    <div class="d-flex align-items-center">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="theme" id="themeLight" value="light">
                                            <label class="form-check-label" for="themeLight">Light</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="theme" id="themeDark" value="dark">
                                            <label class="form-check-label" for="themeDark">Dark</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="theme" id="themeSystem" value="system" checked>
                                            <label class="form-check-label" for="themeSystem">Use System Setting</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <h5 class="mt-4 mb-3">Content</h5>
                                
                                <div class="mb-3">
                                    <label for="defaultView" class="form-label">Default Home View</label>
                                    <select class="form-select" id="defaultView" name="defaultView">
                                        <option value="threads">Forum Threads</option>
                                        <option value="blogs">Blog Posts</option>
                                        <option value="combined" selected>Combined</option>
                                    </select>
                                </div>
                                
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="hideReadPosts" name="hideReadPosts">
                                    <label class="form-check-label" for="hideReadPosts">Hide posts I've already read</label>
                                </div>
                                
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="showActivity" name="showActivity" checked>
                                    <label class="form-check-label" for="showActivity">Show my activity feed</label>
                                </div>
                                
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary" name="preferences_submit">Save Preferences</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    
    <!-- Deactivate Account Modal -->
    <div class="modal fade" id="deactivateModal" tabindex="-1" aria-labelledby="deactivateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deactivateModalLabel">Deactivate Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to deactivate your account? Your profile and content will be hidden until you log in again.</p>
                    <form>
                        <div class="mb-3">
                            <label for="deactivateReason" class="form-label">Please tell us why you're leaving (optional)</label>
                            <select class="form-select" id="deactivateReason">
                                <option value="">Select a reason</option>
                                <option value="temporary">I'll be back later</option>
                                <option value="too_busy">I'm too busy to participate</option>
                                <option value="no_value">I'm not finding value in the platform</option>
                                <option value="other">Other reason</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-warning">Deactivate Account</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Delete Account Modal -->
    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteAccountModalLabel">Delete Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong>Warning:</strong> This action is permanent and cannot be undone.
                    </div>
                    <p>Deleting your account will:</p>
                    <ul>
                        <li>Permanently remove your profile</li>
                        <li>Delete all your threads, blog posts, and comments</li>
                        <li>Remove your likes and bookmarks</li>
                    </ul>
                    <form>
                        <div class="mb-3">
                            <label for="deleteConfirm" class="form-label">Type "DELETE" to confirm</label>
                            <input type="text" class="form-control" id="deleteConfirm" placeholder="DELETE">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" disabled>Delete Account</button>
                </div>
            </div>
        </div>
    </div>

    <?php include "components/footer.php"; ?>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.4/dist/js/bootstrap.bundle.min.js" integrity="sha384-YUe2LzesAfftltw+PEaao2tjU/QATaW/rOitAq67e0CT0Zi2VVRL0oC4+gAaeBKu" crossorigin="anonymous"></script>
    <!-- Custom JS -->
    <script src="partials/js/script.js"></script>
    
    <script>
        // Smooth scrolling for sidebar links
        document.querySelectorAll('.list-group-item').forEach(link => {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href && href.startsWith('#')) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href');
                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        // Update active state
                        document.querySelectorAll('.list-group-item').forEach(item => {
                            item.classList.remove('active');
                        });
                        this.classList.add('active');
                        
                        // Scroll to element
                        window.scrollTo({
                            top: targetElement.offsetTop - 20,
                            behavior: 'smooth'
                        });
                    }
                }
            });
        });
        
        // Delete account confirmation
        const deleteConfirmInput = document.getElementById('deleteConfirm');
        const deleteAccountButton = document.querySelector('#deleteAccountModal .btn-danger');
        
        if (deleteConfirmInput && deleteAccountButton) {
            deleteConfirmInput.addEventListener('input', function() {
                if (this.value === 'DELETE') {
                    deleteAccountButton.removeAttribute('disabled');
                } else {
                    deleteAccountButton.setAttribute('disabled', 'disabled');
                }
            });
        }
        
        // Initialize theme preferences based on current setting
        const themeRadios = document.getElementsByName('theme');
        const savedTheme = localStorage.getItem('theme');
        
        if (savedTheme === 'dark') {
            document.getElementById('themeDark').checked = true;
        } else if (savedTheme === 'light') {
            document.getElementById('themeLight').checked = true;
        } else {
            document.getElementById('themeSystem').checked = true;
        }
        
        // Apply theme when radio buttons change
        themeRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'dark') {
                    localStorage.setItem('theme', 'dark');
                    document.documentElement.setAttribute('data-bs-theme', 'dark');
                    document.body.classList.add('dark-mode');
                    document.body.classList.remove('light-mode');
                } else if (this.value === 'light') {
                    localStorage.setItem('theme', 'light');
                    document.documentElement.setAttribute('data-bs-theme', 'light');
                    document.body.classList.add('light-mode');
                    document.body.classList.remove('dark-mode');
                } else {
                    localStorage.removeItem('theme');
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    document.documentElement.setAttribute('data-bs-theme', prefersDark ? 'dark' : 'light');
                    document.body.classList.add(prefersDark ? 'dark-mode' : 'light-mode');
                    document.body.classList.remove(prefersDark ? 'light-mode' : 'dark-mode');
                }
            });
        });
    </script>
</body>
</html> 