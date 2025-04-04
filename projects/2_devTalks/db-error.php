<?php
// Start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Set HTTP response code to 503 (Service Unavailable)
http_response_code(503);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Connection Error | Dev Talks</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.4/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-DQvkBjpPgn7RC31MCQoOeC9TI2kdqa4+BSgNMNj8v77fdC77Kj5zpWFTJaaAoMbC" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Custom CSS -->
    <style>
        :root {
            --bs-body-bg: #f8f9fa;
            --bs-body-color: #212529;
        }
        
        [data-bs-theme="dark"] {
            --bs-body-bg: #121212;
            --bs-body-color: #f8f9fa;
        }
        
        body {
            background-color: var(--bs-body-bg);
            color: var(--bs-body-color);
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .card {
            background-color: var(--bs-body-bg);
            border: none;
        }
        
        .db-icon {
            font-size: 3rem;
        }
    </style>
    <!-- Set initial theme based on user preference -->
    <script>
        // Check for saved theme preference
        const savedTheme = localStorage.getItem('theme') || 
                        (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        
        // Apply theme class to document
        document.documentElement.setAttribute('data-bs-theme', savedTheme);
    </script>
</head>
<body>
    <main>
        <div class="container">
            <div class="row">
                <div class="col-md-8 mx-auto text-center">
                    <div class="card shadow-lg">
                        <div class="card-body p-5">
                            <div class="db-icon text-warning mb-4">
                                <i class="bi bi-database-x"></i>
                            </div>
                            <h2 class="mb-3">Database Connection Error</h2>
                            <p class="lead mb-4">We're having trouble connecting to our database. This is usually a temporary issue.</p>
                            <hr class="my-4">
                            <p>Here are some things you can try:</p>
                            <ul class="list-group list-group-flush mb-4 text-start">
                                <li class="list-group-item">Wait a few minutes and refresh the page</li>
                                <li class="list-group-item">Check if your internet connection is working properly</li>
                                <li class="list-group-item">Clear your browser cache and cookies</li>
                            </ul>
                            <div class="d-flex justify-content-center gap-3">
                                <a href="index.php" class="btn btn-primary">
                                    <i class="bi bi-arrow-clockwise me-2"></i>Try Again
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4 text-muted">
                        <p>If the problem persists, please contact our support team.</p>
                        <p><small>Error Code: DB_CONN_FAILED</small></p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-dark text-light py-3 text-center">
        <div class="container">
            <p class="mb-0">&copy; <?php echo date('Y'); ?> Dev Talks. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.4/dist/js/bootstrap.bundle.min.js" integrity="sha384-YUe2LzesAfftltw+PEaao2tjU/QATaW/rOitAq67e0CT0Zi2VVRL0oC4+gAaeBKu" crossorigin="anonymous"></script>
</body>
</html> 