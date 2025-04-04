<?php
// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include auth helper
include_once "partials/auth_helper.php";

// For debugging
// debugSession();

echo '
<nav class="navbar navbar-expand-lg bg-dark navbar-dark shadow-lg">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Dev Talks</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="about.php">About</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Categories
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="category.php?cat=web-development">Web Development</a></li>
            <li><a class="dropdown-item" href="category.php?cat=mobile">Mobile</a></li>
            <li><a class="dropdown-item" href="category.php?cat=database">Database</a></li>
            <li><a class="dropdown-item" href="category.php?cat=devops">DevOps</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="categories.php">All Categories</a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="forum.php">Forums</a>
        </li>
      </ul>

      <!-- Right-aligned Search and Buttons -->
      <div class="d-flex flex-column flex-lg-row align-items-center ms-auto">
        ';
        
        if (isLoggedIn()) {
            // Logged in user menu
            echo '
            <div class="d-flex align-items-center">
              <!-- Create buttons -->
              <div class="dropdown me-3">
                <button class="btn btn-success btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="bi bi-plus-lg"></i> Create
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li><a class="dropdown-item" href="create-thread.php">New Thread</a></li>
                  <li><a class="dropdown-item" href="create-blog.php">New Blog Post</a></li>
                </ul>
              </div>

              <!-- User dropdown -->
              <div class="dropdown">
                <button class="btn btn-outline-light btn-sm dropdown-toggle d-flex align-items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <img src="' . (isset($_SESSION['userAvatar']) ? $_SESSION['userAvatar'] : 'partials/img/avatars/default.png') . '" class="avatar avatar-sm me-2" alt="User avatar">
                  ' . htmlspecialchars($_SESSION["username"]) . '
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li><a class="dropdown-item" href="profile.php?id=' . $_SESSION["userId"] . '">
                    <i class="bi bi-person me-2"></i> My Profile
                  </a></li>
                  <li><a class="dropdown-item" href="dashboard.php">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                  </a></li>
                  <li><a class="dropdown-item" href="settings.php">
                    <i class="bi bi-gear me-2"></i> Settings
                  </a></li>
                  <li><hr class="dropdown-divider"></li>
                  <li><a class="dropdown-item text-danger" href="partials/handleLogout.php">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                  </a></li>
                </ul>
              </div>
            </div>';
        } else {
            // Login & Signup buttons for guest users
            echo '
            <div class="d-flex flex-lg-row align-items-center">
              <button type="button" class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#loginModal">Login</button>
              <button type="button" class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#signupModal">Signup</button>
            </div>';
        }
        
        echo '
      </div>
    </div>
  </div>
</nav>
';
?>
