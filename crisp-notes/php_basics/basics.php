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
        <h3>This is the best php notes </h3>
        <br>

        <?php
        echo "Hello world and this is printed using PHP"; //print in php
        // Single line comment
        /*
            This
            is 
            a
            multi
            line
            comment
        */

        //01. VARIABLES:

        $variable1 = 5;
        $variable2 = 2;
        echo "<br>";
        echo $variable1;
        echo "<br>";
        echo $variable2;
        echo "<br>";
        echo "<br>";

        //02. OPERATORS: 

        echo "<h3>Arithmetic operators: </h3>";

        echo "<br>";
        echo "The value of varible1 + variable2 is ";
        echo $variable1 + $variable2;
        echo "<br>";
        echo "The value of varible1 - variable2 is ";
        echo $variable1 - $variable2;
        echo "<br>";
        echo "The value of varible1 * variable2 is ";
        echo $variable1 * $variable2;
        echo "<br>";
        echo "The value of varible1 / variable2 is ";
        echo $variable1 / $variable2;
        echo "<br>";

        echo "<h3>Assignment Operators:</h3>";
        $variable1 = 100;
        $variable2 = $variable1;
        echo "<br>";
        echo "The value of variable1 is now: ";
        echo $variable2;
        echo "<br>";
        $variable2 += 10;
        echo "The value of variable1 is now: ";
        echo $variable2;
        echo "<br>";
        $variable2 -= 10;
        echo "The value of variable1 is now: ";
        echo $variable2;
        echo "<br>";
        $variable2 *= 10;
        echo "The value of variable1 is now: ";
        echo $variable2;
        echo "<br>";
        $variable2 /= 10;
        echo "The value of variable1 is now: ";
        echo $variable2;
        echo "<br>";
        echo "<br>";


        echo "<h3> Comparison Operators </h3>";
        echo "The value of 1==4 is ";
        echo var_dump(1 == 4);
        echo "<br>";

        echo "The value of 1!=4 is ";
        echo var_dump(1 != 4);
        echo "<br>";

        echo "The value of 1>=4 is ";
        echo var_dump(1 >= 4);
        echo "<br>";

        echo "The value of 1<=4 is ";
        echo var_dump(1 <= 4);
        echo "<br>";

        echo "<h3>Increment/Decrement Operators</h3>";
        // echo $variable1++;
        // echo $variable1--;
        // echo ++$variable1;
        echo --$variable1;
        echo "<br>";
        echo $variable1;

        echo " <h3>Logical Operator</h3>";
        // and (&&)
        // or (||)
        // xor 
        // !

        $myVar = (true and true);
        $myVar = (false and true);
        $myVar = (false and false);
        $myVar = (true and false);
        $myVar = (true or false);

        $myVar = (true xor true);
        $myVar = (false and true);
        $myVar = (false xor false);
        $myVar = (true and false);
        echo "<br>";
        echo var_dump($myVar);

        ?>
    </div>


</body>

</html>