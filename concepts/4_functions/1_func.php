<?php

echo "FUNCTIONS in PHP <br>\n";

function totalMarks($marks){
    $sum = 0;
    foreach($marks as $num){
        $sum += $num;
    }
    return $sum;
}

function totalPercentage($marks){
    $total = totalMarks($marks);
    $percentage = ($total / 600) * 100;

    return (float)$percentage;
}

$akash = [80, 95, 98, 96, 85, 97];
echo "Total marks obtained by Akash is: ", totalMarks($akash) 
    . " and his Total %age is: <strong>", number_format(totalPercentage($akash), 2) ." </strong><br>\n\n";

$shruti = [80, 90, 75, 79, 82, 90];   
echo "Total marks obtained by Shruti is: ", totalMarks($shruti) 
    . " and his Total %age is: <strong>", number_format(totalPercentage($shruti), 2) ." </strong><br>\n\n";

?>
