<?php
// String Functions in PHP

// 1. strlen() - Get the length of a string
$string = "Hello, GitHub!";
echo "Length of string: " . strlen($string) . "<br>\n";

// 2. strrev() - Reverse a string
echo "Reversed string: " . strrev($string) . "<br>\n";

// 3. strpos() - Find the position of the first occurrence of a substring
$position = strpos($string, "GitHub");

// 4. str_replace() - Replace all occurrences of a substring with another substring
$replacedString = str_replace("GitHub", "PHP", $string);
echo "Replaced string: " . $replacedString . "<br>\n";

// 5. strtolower() - Convert a string to lowercase
echo "Lowercase string: " . strtolower($string) . "<br>\n";

// 6. strtoupper() - Convert a string to uppercase
echo "Uppercase string: " . strtoupper($string) . "<br>\n";

// 7. substr() - Extract a portion of a string
$substring = substr($string, 7, 6); // Start at position 7, length 6
echo "Substring: " . $substring . "<br>\n";

// 8. trim() - Remove whitespace from the beginning and end of a string
$whitespaceString = "   Hello, PHP!   ";
echo "Trimmed string: '" . trim($whitespaceString) . "'<br>\n";

// 9. explode() - Split a string into an array by a delimiter
$csv = "apple,banana,orange";
$array = explode(",", $csv);
print_r($array);

// 10. implode() - Join array elements into a string with a delimiter
$joinedString = implode(" | ", $array);
echo "Joined string: " . $joinedString . "<br>\n";

?>