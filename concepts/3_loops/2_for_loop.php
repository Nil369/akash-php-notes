<?php
echo "For loops in php <br>\n";

/* SYNTAX:
for(initialization;condition; updation);
*/

for($i = 0; $i < 5; $i++){
    echo "The num is: $i <br>\n";
}

// Avoid running into infinite loops
// for ($i=0; $i < 69; ) { 
//     echo $i;
// }

echo "For loop has ended";

?>