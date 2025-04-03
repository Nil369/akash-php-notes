<?php

function Button(
    $color = "primary",
    $text = "Button",
    $variant = "", 
    $size = "md", 
    $additionalClasses = ""
) {
    $variantClass = ($variant === "outline") ? "btn-outline-$color" : "btn-$color";
    $sizeClass = ($size === "lg" || $size === "sm") ? "btn-$size" : "";
    
    return '<button type="button" class="btn ' . $variantClass . ' ' . $sizeClass . ' ' . $additionalClasses . ' m-2">
            ' . $text . '
          </button>';
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
