<?php
// Load environment variables
require_once __DIR__ . '/env.php';
loadEnv();

// Start session for flash messages only if it's not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fetch DB credentials from .env
$servername = getenv("DB_HOST");
$username   = getenv("DB_USER");
$password   = getenv("DB_PASS");
$dbname     = getenv("DB_NAME");
$port       = getenv("DB_PORT") ? getenv("DB_PORT") : 3306; // Default to 3306 if not specified
$use_ssl    = getenv("DB_SSL") === "true" ? true : false; // Check if SSL is required

// Create database connection with error handling
try {
    if (strpos($servername, 'aivencloud.com') !== false) {
        // Special handling for Aiven cloud databases
        $conn = mysqli_init();
        
        // Force SSL for Aiven
        mysqli_options($conn, MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, false);
        mysqli_options($conn, MYSQLI_CLIENT_SSL, 1);
        mysqli_real_connect(
            $conn, 
            $servername, 
            $username, 
            $password, 
            $dbname, 
            $port,
            NULL,
            MYSQLI_CLIENT_SSL
        );
    } else {
        // Generic cloud database connection
        $conn = mysqli_init();
        
        // Configure SSL if needed
        if ($use_ssl) {
            mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);
            mysqli_options($conn, MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, false);
            
            // Connect with SSL
            mysqli_real_connect($conn, $servername, $username, $password, $dbname, $port, MYSQLI_CLIENT_SSL);
        } else {
            // Connect without SSL
            mysqli_real_connect($conn, $servername, $username, $password, $dbname, $port);
        }
    }
    
    if (!$conn) {
        throw new Exception("Database connection failed: " . mysqli_connect_error());
    }

    // Check if table exists
    $check_table = mysqli_query($conn, "SHOW TABLES LIKE 'notes'");

    if (mysqli_num_rows($check_table) == 0) {
        // Create the table
        $create_table = "CREATE TABLE IF NOT EXISTS notes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            description TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            tag VARCHAR(50) DEFAULT 'General',
            is_pinned BOOLEAN DEFAULT FALSE,
            is_archived BOOLEAN DEFAULT FALSE
        )";

        if (mysqli_query($conn, $create_table)) {
            // Insert sample data
            $sample_data = "INSERT INTO notes (title, description, tag) VALUES 
            ('Meeting Notes', 'Discussion about new project timeline', 'Work'),
            ('Shopping List', 'Milk, Eggs, Bread, Vegetables', 'Personal'),
            ('Book Recommendations', 'The Alchemist, Atomic Habits, Deep Work', 'Learning'),
            ('Project Ideas', 'Create a personal finance tracker app', 'Ideas'),
            ('Workout Plan', 'Monday: Chest, Tuesday: Back, Wednesday: Legs', 'Health')";

            mysqli_query($conn, $sample_data);
        } else {
            throw new Exception("Error creating table: " . mysqli_error($conn));
        }
    }

    // Clear previous error messages
    if (isset($_SESSION['db_error'])) {
        unset($_SESSION['db_error']);
    }

} catch (Exception $e) {
    // Store error message in session
    $_SESSION['db_error'] = $e->getMessage();

    // Redirect to error page
    header("Location: db_error.php");
    exit();
}
?>
