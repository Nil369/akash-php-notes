<?php
// Single Line Comment in php

/*
Multi Line Comment in php.
PHP functions/classes are NOT CASE SENSITIVE. i.e, echo/ECHO/eCHo are all same

NOTE:: <br> adds new line in browser. But using php as programming language we need to use\n 
*/



// ++++++++++++++++++++ PRINTING IN PHP ++++++++++++++++++++++
// Method 1:
echo "Hello World!<br>";  
echo("Hello, World 2<br>"); // Doesn't return anything
// Faster | Multiple arguments can be passed seperated by .

// Method 2:
print("Hello This is an example of print statement in php\n<br>"); 
// slower | Takes single argument | Returns the value 1



// +++++++++++++++++++++++++ VARIABLES +++++++++++++++++++++++++++
/*
-> Variables are containers for storing some values in memory
-> In php variables starts with '$'
-> vars r CASE SENSITIVE i.e, name,Name & nAme are 3 diff. varibles

RULES :-
🔸A variable in PHP always starts with a dollar sign.
🔸It cannot start with a number.
🔸A variable can either start with a letter or an underscore
🔸It can only hold alphanumeric characters or underscore.
🔸You can use an alphanumeric character while assigning the name of a variable. 
    For example, using symbols such as “&” or “%” in variable names will produce an error.
🔸Variables in PHP are case-sensitive. It means “$_NAME” and “$_name” are two different variables.
*/

$name = "Akash";
$age = 19;
$income = 700000000;

echo "That guy is called $name. <br> He is $age years old and he earns $income monthly!<br>";

echo "$name is a good boy<br>";
echo "$name is a real gangsta!<br>"

?>