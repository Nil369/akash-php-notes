<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Basics</title>
</head>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .container {
        max-width: 80%;
        background-color: rgb(25, 1, 48);
        margin: 5vw 20vw;
        padding: 23px;
        color: white;
        border-radius: 15px;
    }
    .container:hover{
        scale: 120%;
    }
    h1,
    h3 {
        color: yellow;
    }
</style>

<body>
    <div class="container">
        <h1>LEARN PHP : </h1>
        <h3>This is the best php notes part-2</h3>
        <br>

        <?php
        // constants in php:   
        define('PI', 3.14);
        $radius = 69;
        $area = PI * $radius * $radius;
        echo $area;
        echo "<br>";

        // Conditionals in php:
        $age = 6;
        if ($age > 18) {
            echo "You can go to the party";
            echo "<br>";
        } else if ($age == 7) {
            echo "You are 7 years old";
            echo "<br>";
        } else if ($age == 6) {
            echo "You are 6 years old";
            echo "<br>";
        } else {
            echo "You can not go to the party";
            echo "<br>";
        }

        // Arrays in php
        $languages = array("Python", "C++", "php", "NodeJs");
        echo count($languages);
        echo "<br>";
        echo $languages[0];
        echo "<br>";

        // Loops in PHP
        $a = 0;
        while ($a <= 10) {
            echo "<br>The value of a is: ";
            echo $a;
            $a++;
        }
        // Iterating arrays in PHP using while loop
        $a = 0;
        while ($a < count($languages)) {
            echo "<br>The value of language is: ";
            echo $languages[$a];
            $a++;
        }
        // Do while loop
        $a = 200;
        do {
            echo "<br>The value of a is: ";
            echo $a;
            $a++;
        } while ($a < 10);
        // For loop
        for ($a = 60; $a < 10; $a++) {
            echo "<br>The value of a from the for loop is: ";
            echo $a;
        }
        foreach ($languages as $value) {
            echo "<br>The value from foreach loop is ";
            echo $value;
        }

        // Functions in php: 

        function print_number($number)
        {
            echo "<br>Your number is ";
            echo $number;
        }

        // *** STRINGS in php: ***

        $str = "This this this";
        echo $str . "<br>";
        $lenn = strlen($str);
        echo "The length of this string is " . $lenn . ". Thank you <br>";
        echo "The number of words in this string is " . str_word_count($str) . ". Thank you <br>";
        echo "The reversed string is " . strrev($str) . ". Thank you <br>";
        echo "The search for is in this string is " . strpos($str, "is") . ". Thank you <br>";
        echo "The replaced string is " . str_replace("is", "at", $str) . ". Thank you <br>";
        // echo $lenn;



        ?>
    </div>


</body>

</html>