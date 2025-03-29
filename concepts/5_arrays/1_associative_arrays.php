<?php
// These are called indexed arrays:
// $arr = array('Hello', 'this', 'is', 'Akash');
// echo $arr[0];
// echo $arr[1];
// echo $arr[2];
// echo $arr[3];


// Assosiative Arrays => It associates the key with values
$favColours = [
    "Akash" => "Blue",
    "Shruti" => "Blue",
    "Nil369" => "BlueViolet",
    "Akhi" => "Pink"
];

echo $favColours['Nil369'] . "<br>\n";
echo $favColours['Akash'] . "<br>\n";


// Printing associative arrays:
foreach ($favColours as $key => $val) {
    echo "Favourite Colour of $key is: $val <br>\n";
}


?>