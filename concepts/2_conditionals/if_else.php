<?php

$num = (int) readline("Enter a number: "); // Taking input as string and typecasting to an integer

if ($num % 2 == 0) {
    echo "$num is even <br>\n";
} else {
    echo "$num is odd <br>\n";
}


$age = (int) readline("Enter your age: ");

if ($age >= 18 && $age <= 100) {
    echo "You are eligible to vote";
} elseif ($age < 15) {
    echo "You need to atleast 18 years to vote";
} else {
    echo "You are still a kid";
}


?>