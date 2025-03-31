<?php
// Start session for flash messages only if it's not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$servername = "localhost";
$username = "root";
$password = "";

// Function to check if XAMPP/MySQL is running
function is_xampp_running() {
    $connection = @fsockopen("localhost", 3306); 
    if (is_resource($connection)) {
        fclose($connection);
        return true;
    }
    return false;
}

// Create database connection with error handling
try {
    // Check if XAMPP is running
    if (!is_xampp_running()) {
        throw new Exception("MySQL server is not running. Please start XAMPP MySQL service.");
    }
    
    // Create connection without selecting database
    $conn = mysqli_connect($servername, $username, $password);
    
    if (!$conn) {
        throw new Exception("Database connection failed: " . mysqli_connect_error());
    }
    
    // Create database if it doesn't exist
    $sql = "CREATE DATABASE IF NOT EXISTS inotes";
    
    if (!mysqli_query($conn, $sql)) {
        throw new Exception("Error creating database: " . mysqli_error($conn));
    }
    
    // Select the database
    mysqli_select_db($conn, "inotes");
    
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
    
    // Clear any previous error messages if connection is successful
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
