<?php
// Disable error hiding temporarily
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Load environment variables
require_once "./_db/env.php";
loadEnv();

// Get connection parameters
$servername = getenv("DB_HOST");
$username   = getenv("DB_USER");
$password   = getenv("DB_PASS");
$dbname     = getenv("DB_NAME");
$port       = getenv("DB_PORT") ? getenv("DB_PORT") : 3306;
$use_ssl    = getenv("DB_SSL") === "true" ? true : false;

echo "<h1>Database Connection Test</h1>";
echo "<p>Testing connection to: <strong>$servername:$port</strong><br>";
echo "Database name: <strong>$dbname</strong><br>";
echo "Username: <strong>$username</strong><br>";
echo "SSL Enabled: <strong>" . ($use_ssl ? "Yes" : "No") . "</strong></p>";

// Test basic connectivity to server
echo "<h2>1. Testing server connectivity:</h2>";
$socket = @fsockopen($servername, $port, $errno, $errstr, 5);
if (!$socket) {
    echo "<p style='color: red;'>Could not connect to $servername:$port - $errstr ($errno)</p>";
    echo "<p>This suggests a network issue, firewall block, or incorrect host/port.</p>";
} else {
    fclose($socket);
    echo "<p style='color: green;'>Socket connection successful!</p>";
}

// Test MySQL connection
echo "<h2>2. Testing MySQL connection:</h2>";

try {
    if (strpos($servername, 'aivencloud.com') !== false) {
        // Special handling for Aiven
        echo "<p>Detected Aiven Cloud database, using special connection method...</p>";
        
        $conn = mysqli_init();
        mysqli_options($conn, MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, false);
        mysqli_options($conn, MYSQLI_CLIENT_SSL, 1);
        
        // Try to connect
        if (!@mysqli_real_connect($conn, $servername, $username, $password, $dbname, $port, NULL, MYSQLI_CLIENT_SSL)) {
            throw new Exception(mysqli_connect_error());
        }
    } else {
        // Generic cloud connection
        $conn = mysqli_init();
        
        if ($use_ssl) {
            echo "<p>Attempting connection with SSL...</p>";
            mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);
            mysqli_options($conn, MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, false);
            
            if (!@mysqli_real_connect($conn, $servername, $username, $password, $dbname, $port, MYSQLI_CLIENT_SSL)) {
                throw new Exception(mysqli_connect_error());
            }
        } else {
            echo "<p>Attempting connection without SSL...</p>";
            if (!@mysqli_real_connect($conn, $servername, $username, $password, $dbname, $port)) {
                throw new Exception(mysqli_connect_error());
            }
        }
    }
    
    echo "<p style='color: green;'>MySQL connection successful!</p>";
    
    // Test query execution
    echo "<h2>3. Testing query execution:</h2>";
    if ($result = mysqli_query($conn, "SHOW TABLES")) {
        echo "<p style='color: green;'>Query executed successfully!</p>";
        echo "<p>Tables in database:</p><ul>";
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_row($result)) {
                echo "<li>$row[0]</li>";
            }
        } else {
            echo "<li>No tables found</li>";
        }
        echo "</ul>";
        mysqli_free_result($result);
    } else {
        echo "<p style='color: red;'>Query failed: " . mysqli_error($conn) . "</p>";
    }
    
    mysqli_close($conn);
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Connection failed: " . $e->getMessage() . "</p>";
    
    echo "<h2>Troubleshooting tips:</h2>";
    echo "<ol>";
    echo "<li>Double-check your database credentials in the .env file</li>";
    echo "<li>Ensure your database allows connections from your current IP address</li>";
    echo "<li>Verify the DB_PORT is correct (Aiven typically uses a non-standard port)</li>";
    echo "<li>Make sure DB_SSL is set to 'true' for Aiven databases</li>";
    echo "<li>Check if your server has outbound firewall rules blocking the connection</li>";
    echo "</ol>";
}

// Display PHP and extension information for debugging
echo "<h2>PHP Configuration:</h2>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>MySQLi Extension loaded: " . (extension_loaded('mysqli') ? 'Yes' : 'No') . "</p>";
echo "<p>OpenSSL Extension loaded: " . (extension_loaded('openssl') ? 'Yes' : 'No') . "</p>";
?> 