<?php

function Button($type, $text, $url = "", $size = "md") {
    // Generate button based on type
    $btnClass = "btn btn-$type btn-$size";
    
    // If button is for login/signup, add data attributes for modal
    if ($text == "Login") {
        return '<button type="button" class="' . $btnClass . ' me-2" data-bs-toggle="modal" data-bs-target="#loginModal">' . $text . '</button>';
    } else if ($text == "Signup") {
        return '<button type="button" class="' . $btnClass . '" data-bs-toggle="modal" data-bs-target="#signupModal">' . $text . '</button>';
    } else if (!empty($url)) {
        // Regular URL button
        return '<a href="' . $url . '" class="' . $btnClass . '">' . $text . '</a>';
    } else {
        // Regular button
        return '<button type="button" class="' . $btnClass . '">' . $text . '</button>';
    }
}

?>
