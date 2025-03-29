<?php

$groceries = ["Bananas", "Apples", "Harpic", "Bread", "Butter","Sugar"];
// Method 1: using for-loop
for($i=0; $i<count($groceries); $i++){
    echo $groceries[$i];
    echo " <br>\n";
}



// Method 2:
echo "\n<br>For Each Loops in PHP: <br>\n";
// For each loops are used to iterate over every elements of an array easily

foreach($groceries as $val){
    echo "$val <br>\n";
}

?>