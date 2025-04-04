<?php
// Start session
session_start();

// Dump session information
echo '<h2>Session Debug Information</h2>';
echo '<pre>';
var_dump($_SESSION);
echo '</pre>';

// Check if user ID exists in session
echo '<h3>Authentication Status</h3>';
if (isset($_SESSION['userId'])) {
    echo "Logged in as user: " . $_SESSION['username'] . " (ID: " . $_SESSION['userId'] . ")";
} else {
    echo "Not logged in. No user session found.";
}

// Add test login/logout buttons
echo '<h3>Test Actions</h3>';
echo '<a href="session_test.php?action=login" class="btn btn-success">Set Test Session</a> ';
echo '<a href="session_test.php?action=logout" class="btn btn-danger">Clear Session</a>';

// Process test actions
if (isset($_GET['action'])) {
    if ($_GET['action'] === 'login') {
        $_SESSION['userId'] = 999;
        $_SESSION['username'] = 'TestUser';
        echo '<p>Test session created. <a href="session_test.php">Refresh</a> to see changes.</p>';
    } elseif ($_GET['action'] === 'logout') {
        session_unset();
        session_destroy();
        echo '<p>Session destroyed. <a href="session_test.php">Refresh</a> to see changes.</p>';
    }
}

// Test header logic
echo '<h3>Header User Display Test</h3>';
echo '<div style="background-color: #343a40; color: white; padding: 15px;">';
if (isset($_SESSION['userId'])) {
    echo '
    <div style="display: flex; align-items: center;">
        <div style="margin-right: 10px;">
            <button class="btn btn-success btn-sm">
                <i class="bi bi-plus-lg"></i> Create
            </button>
        </div>
        <div>
            <button class="btn btn-outline-light btn-sm">
                ' . htmlspecialchars($_SESSION['username']) . '
            </button>
        </div>
    </div>';
} else {
    echo '
    <div style="display: flex; align-items: center;">
        <button class="btn btn-primary btn-sm" style="margin-right: 10px;">Login</button>
        <button class="btn btn-outline-light btn-sm">Signup</button>
    </div>';
}
echo '</div>';
?> 