<?php
// What is a session?
// Used to manage information across difference pages

// Verify the user login info
session_start();
$_SESSION['username'] = "Akash";
$_SESSION['favCat'] = "Anime";
echo "We have saved your session";

?>

