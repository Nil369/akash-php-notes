<?php 
// Operators in php:
/* 
1. Arithmetic Operators
2. Assignment Operators
3. Comaprison Operators
4. Logical Operators 
*/



// 1. Arithemetic Opeartors
$a = 10;
$b = 5;
echo "a + b = ". ($a + $b) ." <br>\n";
echo "a - b = ". ($a - $b) ." <br>\n";
echo "a * b = ". ($a * $b) ." <br>\n";
echo "a / b = ". ($a / $b) ." <br>\n";
echo "a % b = ". ($a % $b) ." <br>\n";
echo "a ** b = ". ($a ** $b) ." <br><br>\n\n";



// 2. Assignment Operators
$var = 20; //Initial Value

$var+=20; // added 20 and assigned the value to the same variable
echo "var = $var <br>\n";

$var -= 10;
echo "var = $var <br>\n";

$var *= 10;
echo "var = $var <br>\n";

$var /= 10;
echo "var = $var <br>\n";

$var %= 20;
echo "var = $var <br>\n";

$var **= 2;
echo "var = $var <br><br>\n\n";



// 3. Comparison Operators:
$x = 69;
$y = 88;

echo "x == y: " . var_dump($x == $y) . "<br>\n"; // Equal
echo "x != y: " . var_dump($x != $y) . "<br>\n"; // Not equal
echo "x > y: " . var_dump($x > $y) . "<br>\n";   // Greater than
echo "x < y: " . var_dump($x < $y) . "<br>\n";   // Less than
echo "x >= y: " . var_dump($x >= $y) . "<br>\n"; // Greater than or equal to
echo "x <= y: " . var_dump($x <= $y) . "<br>\n"; // Less than or equal to
echo "x === y: " . var_dump($x === $y) . "<br>\n"; // Identical (equal and same type)
echo "x !== y: " . var_dump($x !== $y) . "<br><br>\n\n"; // Not identical



// 4. Logical Operators:
$m = true;
$n = true;

echo "For m and n, the result is "; 
echo var_dump($m and $n) . "<br>\n";

echo "For m && n, the result is "; 
echo var_dump($m && $n). "<br>\n";

echo "For m or n, the result is "; 
echo var_dump($m or $n). "<br>\n";

echo "For m || n, the result is "; 
echo var_dump($m || $n). "<br>\n";

echo "For !m , the result is "; 
echo var_dump(!$m). "<br><br>\n\n";


?>