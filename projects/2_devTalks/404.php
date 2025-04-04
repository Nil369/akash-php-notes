<?php
// Start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Set HTTP response code to 404
http_response_code(404);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found | Dev Talks</title>
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
    ?>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 mx-auto text-center">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-5">
                        <h1 class="display-1 text-primary mb-4"><i class="bi bi-exclamation-circle"></i></h1>
                        <h2 class="display-4 mb-3">404</h2>
                        <h3 class="mb-4">Page Not Found</h3>
                        <p class="lead mb-4">Oops! The page you're looking for doesn't exist or has been moved.</p>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="index.php" class="btn btn-primary">
                                <i class="bi bi-house-door me-2"></i>Go Home
                            </a>
                            <a href="javascript:history.back()" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Go Back
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <p>Looking for something specific? Try one of these:</p>
                    <div class="d-flex flex-wrap justify-content-center gap-2 mt-3">
                        <a href="index.php" class="btn btn-sm btn-outline-primary">Home</a>
                        <a href="forum.php" class="btn btn-sm btn-outline-primary">Forums</a>
                        <a href="blogs.php" class="btn btn-sm btn-outline-primary">Blogs</a>
                        <a href="category.php?cat=web-development" class="btn btn-sm btn-outline-primary">Web Development</a>
                        <a href="category.php?cat=mobile" class="btn btn-sm btn-outline-primary">Mobile Development</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include "components/footer.php"; ?>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.4/dist/js/bootstrap.bundle.min.js" integrity="sha384-YUe2LzesAfftltw+PEaao2tjU/QATaW/rOitAq67e0CT0Zi2VVRL0oC4+gAaeBKu" crossorigin="anonymous"></script>
    <!-- Custom JS -->
    <script src="partials/js/script.js"></script>
</body>
</html> 