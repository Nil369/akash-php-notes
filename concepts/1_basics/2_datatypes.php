<?php 

// Datatypes in php:
/*
1. String
2. Integer
3. Float
4. Boolean
5. Objects
6. Arrays
7. NULL
*/


// Strings:sequence of characters enclosed in single or double quotes
$employee_name = "John Doe";
echo "Employee Name: <strong>". $employee_name ."</strong><br>\n";


// Integers: whole numbers (positive, negative, or zero) without decimals.
$bus_seats = 50;
echo "Total seats in the bus: " . $bus_seats ."<br>\n";


// Float: numbers with decimal points (also called “double” in PHP).
$product_price = 99.99;
echo "Price of the product: $" . $product_price ."<br>\n";


// Boolean: Can only be true or false
$is_user_premium = false;
$is_admin = true;
echo var_dump($is_user_premium) ."<br>\n";
echo var_dump($is_admin) ."<br>\n";


// Objects: A user-defined complex data type that represents real-world entities with properties and behaviors.
//Objects are instances of a class...will explore it later! It is basically a part of OOP in php!


// Arrays: A data type that holds multiple values in a single variable.
$items = ["Milk", "Eggs", "Bread"];
echo var_dump($items), "<br>\n";

$nums = array(1,2,4,18,20);
echo var_dump($nums), "<br>\n";
echo "I need to buy: " . implode(", ", $items);// it is used to join array elements

// NULL
$booked_room = NULL;
echo $booked_room."<br>\n";
echo var_dump($booked_room);


?>