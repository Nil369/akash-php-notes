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
    // For Aiven Cloud MySQL
    if (strpos($servername, 'aivencloud.com') !== false) {
        // Build PDO DSN for Aiven MySQL
        $dsn = "mysql:host=$servername;port=$port;dbname=$dbname";
        
        // Set PDO options for SSL
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            // Force SSL mode with available constants
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
        ];
        
        // Create the PDO connection
        $conn = new PDO($dsn, $username, $password, $options);
        
        // Force SSL mode with a query after connection
        $conn->exec("SET SESSION ssl_mode = 'REQUIRED'");
    } else {
        // Regular connection options
        $dsn = "mysql:host=$servername;port=$port;dbname=$dbname";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        
        // Add SSL if needed
        if ($use_ssl) {
            $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
            
            // Create connection
            $conn = new PDO($dsn, $username, $password, $options);
            
            // Force SSL mode with a query
            $conn->exec("SET SESSION ssl_mode = 'REQUIRED'");
        } else {
            // Create connection without SSL
            $conn = new PDO($dsn, $username, $password, $options);
        }
    }
    
    // Check if notes table exists
    $stmt = $conn->query("SHOW TABLES LIKE 'notes'");
    if ($stmt->rowCount() == 0) {
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
        
        $conn->exec($create_table);
        
        // Insert sample data
        $sample_data = "INSERT INTO notes (title, description, tag) VALUES 
        ('Meeting Notes', 'Discussion about new project timeline', 'Work'),
        ('Shopping List', 'Milk, Eggs, Bread, Vegetables', 'Personal'),
        ('Book Recommendations', 'The Alchemist, Atomic Habits, Deep Work', 'Learning'),
        ('Project Ideas', 'Create a personal finance tracker app', 'Ideas'),
        ('Workout Plan', 'Monday: Chest, Tuesday: Back, Wednesday: Legs', 'Health')";
        
        $conn->exec($sample_data);
    }
    
    // Clear previous error messages
    if (isset($_SESSION['db_error'])) {
        unset($_SESSION['db_error']);
    }
    
} catch (PDOException $e) {
    // Store error message in session
    $_SESSION['db_error'] = $e->getMessage();
    
    // Redirect to error page
    header("Location: db_error.php");
    exit();
}
?> 