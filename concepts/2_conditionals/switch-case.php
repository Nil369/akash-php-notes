<?php

$day = readline("Enter a day(e.g: Monday):-  ");

switch ($day) {
    case "Monday":
        echo "Today is Monday. Start of the work week!";
        break;
    case "Tuesday":
        echo "Today is Tuesday. Keep going!";
        break;
    case "Wednesday":
        echo "Today is Wednesday. Midweek already!";
        break;
    case "Thursday":
        echo "Today is Thursday. Almost there!";
        break;
    case "Friday":
        echo "Today is Friday. Weekend is near!";
        break;
    case "Saturday":
        echo "Today is Saturday. Enjoy your weekend!";
        break;
    case "Sunday":
        echo "Today is Sunday. Relax and recharge!";
        break;
    default:
        echo "Invalid day!";
        break;
}

?>