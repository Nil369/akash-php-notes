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

        // Fetch and display data
        $sql = "SELECT * FROM `trip`";
        $result = mysqli_query($conn, $sql);

        $num = mysqli_num_rows($result);
        echo $num . " records found in the DataBase<br>";

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

        // Delete data where destination is 'Delhi'
        $delete_sql = "DELETE FROM `trip` WHERE `dest` = 'Delhi' LIMIT 1";
        $delete_result = mysqli_query($conn, $delete_sql);
        $affected_rows = mysqli_affected_rows($conn);

        echo "<br>Number of affected rows: $affected_rows <br>";
        if ($delete_result) {
            echo "<p class='text-success'>Record deleted successfully</p>";
        } else {
            $err = mysqli_error($conn);
            echo "<p class='text-danger'>Could not delete the record due to this error: $err</p>";
        }
        ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>