<?php
// Include authentication functions
require_once "auth/auth_functions.php";

// Check if user is logged in
$user_id = check_login();

require_once "_db/db_connect.php";

// Check if ID is provided
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

// Update note if form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $tag = !empty($_POST['tag']) ? trim($_POST['tag']) : 'General';
    $is_pinned = isset($_POST['is_pinned']) ? 1 : 0;
    
    // Use prepared statement to prevent SQL injection
    $sql = "UPDATE notes SET 
            title = ?, 
            description = ?, 
            tag = ?, 
            is_pinned = ? 
            WHERE id = ? AND user_id = ?";
    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssiii", $title, $description, $tag, $is_pinned, $id, $user_id);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}

// Get note data - make sure it belongs to the logged-in user
$sql = "SELECT * FROM notes WHERE id = ? AND user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ii", $id, $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    // Note not found or doesn't belong to user
    header("Location: index.php");
    exit();
}

$note = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Note - iNotes</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
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
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            margin-left: 10px;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .container {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <i class="fas fa-sticky-note me-2"></i>iNotes
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?view=archived">Archived</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-1"></i> <?php echo htmlspecialchars($_SESSION['username']); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="auth/profile.php"><i class="fas fa-id-card me-2"></i>Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="auth/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </li>
                <!-- Theme Toggle Button -->
                <li class="nav-item">
                    <div class="theme-toggle btn btn-outline-light ms-2" id="themeToggle">
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
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Note</h4>
                </div>
                <div class="card-body">
                    <form action="edit.php?id=<?php echo $id; ?>" method="POST">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($note['title']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="6" required><?php echo htmlspecialchars($note['description']); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="tag" class="form-label">Tag</label>
                            <input type="text" class="form-control" id="tag" name="tag" value="<?php echo htmlspecialchars($note['tag']); ?>">
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="is_pinned" name="is_pinned" value="1" <?php echo $note['is_pinned'] ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="is_pinned">
                                Pin this note
                            </label>
                        </div>
                        <div class="d-flex flex-column flex-md-row justify-content-between">
                            <a href="index.php" class="btn btn-secondary mb-2 mb-md-0">Cancel</a>
                            <button type="submit" class="btn btn-warning">Update Note</button>
                        </div>
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