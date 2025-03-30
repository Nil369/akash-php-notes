<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contact Us</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="/akash-php-notes/concepts/6_forms_db_crud/6_Inserting_data_using_forms.php">Contacts</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/akash-php-notes/concepts/6_forms_db_crud/6_Inserting_data_using_forms.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // extracting values from the FORM
        $name = $_POST['name'];
        $email = $_POST['email'];
        $desc = $_POST['desc'];

        // DB config
        $hostname = "localhost";
        $username = "root";
        $password = "";
        $database = "akash_db";


        // Creating a connection
        $conn = mysqli_connect($hostname, $username, $password, $database);

        // validating for successfull connection
        if (!$conn) {
            die("<p style='color: red;'>Sorry we failed to connect to Database: " . mysqli_connect_error() . "</p><br>\n");
        } else {
            $sql = "INSERT INTO `contacts` (`name`, `email`, `message`, `dt`) VALUES ('$name', '$email', '$desc', current_timestamp())";
            $res = mysqli_query($conn, $sql);

            if ($res) {
                echo '
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>SUCCESS!</strong> Your Concern has been submitted successfully!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
            } else {
                echo '
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                   <strong>ERROR!</strong> Submitting Your Message. Sorry for the inconvenience.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
            }
        }
    }
    ?>

    <div class="container mt-5 my-form">
        <h1>Contact Us</h1>
        <form action="/akash-php-notes/concepts/6_forms_db_crud/6_Inserting_data_using_forms.php" method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="message" class="form-label">Message</label>
                <textarea class="form-control" id="message" name="desc" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-outline-info">Submit</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>