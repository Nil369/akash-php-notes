<?php

// Creating a 2 dimensional array
$multiDim = [
    [2,5,7,8],
    [1,2,3,1],
    [4,5,0,1]
];

// echo var_dump($multiDim);
// echo $multiDim[1][2];

// Printing the contents of a 2 dimensional array

// for ($i=0; $i < count($multiDim); $i++) { 
//     echo var_dump($multiDim[$i]);
//     echo "<br>\n";
// }

for ($i=0; $i < count($multiDim); $i++) { 
    for ($j=0; $j < count($multiDim[$i]); $j++) { 
        echo $multiDim[$i][$j];
        echo "  ";
    }
    echo "<br>\n";
}

?>
