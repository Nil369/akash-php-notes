<?php
// Start the session and get the data
session_start();
session_unset(); // reset the Global session data
session_destroy(); // destroy the session

echo "You have been logged out <br>\n";
?>
