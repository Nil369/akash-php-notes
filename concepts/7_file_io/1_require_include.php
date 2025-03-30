<?php

/*
1️⃣ Error Handling: include "/partials/_dbconnect.php" | require "/partials/_dbconnect.php"
-> include generates a warning (E_WARNING) if the file is missing but continues execution.
-> require generates a fatal error (E_COMPILE_ERROR) if the file is missing and stops execution immediately.

2️⃣ Use Case:
-> include is used for optional files, such as sidebars, ads, or analytics scripts.
-> require is used for critical files, such as database connections or configuration files.

3️⃣ Performance Impact:
-> Both work similarly, but using include may cause unexpected issues if a required file fails to load.
-> require ensures that the script doesn't run with missing dependencies, preventing potential errors later.

Rule of Thumb 👍: Use require for essential files & include for optional ones!
*/

require __DIR__ . "../partials/_dbconnect.php";

$sql = "SELECT * FROM `trip`";
$result = mysqli_query($conn, $sql);

// Find the number of records returned
$num = mysqli_num_rows($result);
echo "$num records found in the DataBase<br><br>\n\n";

while($row = mysqli_fetch_assoc($result)){ 
    echo $row['sno'] .  ". Hello ". $row['name'] ." Welcome to ". $row['dest'];
    echo "<br>";
}

?>