<?php
// Include auth helper
include_once 'auth_helper.php';

// Logout user
logout();

// Redirect to index.php with success message
header("Location: ../index.php?status=success&message=You have been successfully logged out.");
exit;
?> 