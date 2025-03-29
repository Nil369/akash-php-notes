<?php
echo "while loops in php <br>\n";

// echo 1 . "<br>\n";
// echo 2 . "<br>\n";
// echo 3 . "<br>\n";
// echo 4 . "<br>\n";
// echo 5 . "<br>\n";

/* SYNTAX:
while (condition){
    some lines of code here;
}
*/

$i = 0; 
while($i<5){
    echo "The value of i is ";
    echo $i+1;
    echo " <br>\n";
    $i+=1; 
} 

echo "While loop has ended";

?>
