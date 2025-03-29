<?php
$a = 98; // Global Variable
$b = 9;

function printValue(){
    // $a = 97; // Local Variable
    global $a, $b; // Gives the access to the global variable
    $a = 100;
    $b = 1000;
    echo "<br>\nThe value of your variable a is $a and b is $b";
}

echo $a; 
printValue();
echo "<br>\nThe value of your variable a is $a and b is $b";
echo "<br>\n";
// echo var_dump($GLOBALS); 
echo var_dump($GLOBALS["a"]);
echo var_dump($GLOBALS["b"]);

?>
