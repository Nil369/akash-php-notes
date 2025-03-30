<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>

    <div class="container">
        <?php
        // Connecting to the Database
        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "akash_db";

        // Create a connection
        $conn = mysqli_connect($servername, $username, $password, $database);
        // Die if connection was not successful
        if (!$conn) {
            die("<p class='text-danger'>Sorry we failed to connect: " . mysqli_connect_error() . "</p><br>\n");
        } else {
            echo "<b class='text-success'>Connection was successful</b><br>";
        }

        $sql = "SELECT * FROM `trip`";
        $result = mysqli_query($conn, $sql);

        // Find the number of records returned
        $num = mysqli_num_rows($result);
        echo $num;
        echo " records found in the DataBase<br>";

        // Display the rows returned by the sql query
        if ($num > 0) {
            echo '<table class="table table-striped table-hover mt-4">';
            echo '<thead class="table-primary">';
            echo '<tr>';
            echo '<th scope="col">Sl. No.</th>';
            echo '<th scope="col">Name</th>';
            echo '<th scope="col">Destination</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            // $row = mysqli_fetch_assoc($result);
            // echo var_dump($row);
            // echo "<br>";
            // $row = mysqli_fetch_assoc($result);
            // echo var_dump($row);
            // echo "<br>";

            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<th scope="row">' . $row['sno'] . '</th>';
                echo '<td>' . $row['name'] . '</td>';
                echo '<td>' . $row['dest'] . '</td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
        }
        ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>