<?php
echo "Welcome to the world of cookies<br>";

// Cookies: Used to uniquely identify a user 
// Sessions: Used to securely store user Details in the server

// Syntax to set a cookie
// echo time();
setcookie("category", "Books", time() + 86400, "/"); 
echo "The cookie is set<br>";

?>
