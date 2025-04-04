<?php
/**
 * Authentication Helper Functions
 * This file contains helper functions for authentication and session management
 */

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if a user is logged in
 * @return bool True if user is logged in, false otherwise
 */
function isLoggedIn() {
    return isset($_SESSION['userId']) && !empty($_SESSION['userId']);
}

/**
 * Get the current user's ID
 * @return int|null User ID if logged in, null otherwise
 */
function getCurrentUserId() {
    return isLoggedIn() ? $_SESSION['userId'] : null;
}

/**
 * Get the current username
 * @return string|null Username if logged in, null otherwise
 */
function getCurrentUsername() {
    return isLoggedIn() ? $_SESSION['username'] : null;
}

/**
 * Get the current user's role
 * @return string|null User role if set, null otherwise
 */
function getCurrentUserRole() {
    return isLoggedIn() && isset($_SESSION['userRole']) ? $_SESSION['userRole'] : null;
}

/**
 * Require user to be logged in
 * Redirects to login page if not logged in
 * @param string $redirect_url URL to redirect to after login (optional)
 */
function requireLogin($redirect_url = '') {
    if (!isLoggedIn()) {
        // Store the current URL for redirection after login (if specified)
        if (!empty($redirect_url)) {
            $_SESSION['redirectUrl'] = $redirect_url;
        } else if (!empty($_SERVER['REQUEST_URI'])) {
            $_SESSION['redirectUrl'] = $_SERVER['REQUEST_URI'];
        }
        
        // Redirect to login page
        header("Location: index.php");
        exit();
    }
}

/**
 * Destroy the current session (log out)
 */
function logout() {
    // Clear all session variables
    $_SESSION = array();
    
    // Destroy the session
    session_destroy();
    
    // Delete remember-me cookie if it exists
    if (isset($_COOKIE['rememberme'])) {
        setcookie('rememberme', '', time() - 3600, '/');
    }
}

/**
 * Debug function to print session data
 */
function debugSession() {
    echo '<pre>';
    echo 'Session ID: ' . session_id() . "\n";
    echo 'Session Status: ' . (isLoggedIn() ? 'Logged In' : 'Not Logged In') . "\n";
    echo 'Session Data: ';
    print_r($_SESSION);
    echo '</pre>';
}
?> 