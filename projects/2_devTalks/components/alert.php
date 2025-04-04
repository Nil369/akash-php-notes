<?php

function ShowAlert($message, $type = "primary", $dismissible = true) {
    $dismissButton = $dismissible ? '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' : '';
    $dismissClass = $dismissible ? 'alert-dismissible fade show' : '';
    
    return '
    <div class="alert alert-' . $type . ' ' . $dismissClass . '" role="alert">
        ' . $message . '
        ' . $dismissButton . '
    </div>';
}

?>
