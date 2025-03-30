<?php

// Start the session and get the data
session_start();

if(isset($_SESSION['username'])){
    echo "Welcome ". $_SESSION['username'];
    echo "<br> Your favorite category is ". $_SESSION['favCat'];
    echo "<br>";
}else{
    echo "
    <b> Please Login in to Continue!<br>
        <a href='/akash-php-notes/concepts/8_sessions_cookies/sessions/set_session.php'> Click Here </a>
    </b><br>\n";
}

?>