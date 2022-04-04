<?php

include "../partials/conn.php";
session_start();
if(isset($_SESSION) and isset($_SESSION["adminLogIn"]) and isset($_SESSION["adminUsername"])){
    echo "hello";
    header("location: ./question/");
}

$logInForm = "
        <div class='container md-6 sm-6'>
                <h3 class='my-5 text-center'>Login as Admin</h3>
                <form class='mx-auto border border-dark p-5 rounded border-3' id='adminForm'>

                    <div class='mb-3'>
                        <label for='username' class='form-label'>Username</label>
                        <input type='text' class='form-control' id='username' name='username' required>
                    </div>
                    <div class='mb-3'>
                        <label for='password' class='form-label'>Password</label>
                        <input type='password' class='form-control' id='password' name='password' required>
                    </div>
                    <button type='submit' class='btn btn-primary'>Log In</button>

                </form>
        </div>

";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include "../partials/linkAndMeta.php"; ?>
    <title>Admin || log-in</title>
    <link rel="stylesheet" href="../static/admin.css">
    <script defer src="./index.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-info">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">ADMIN</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="../index.php">Home</a>
            </li>
        </ul>
        </div>
    </div>
    </nav>

    <section>
        <div class="container">
            <?php
                echo $logInForm;
            ?>
        </div>
    </section>

</body>
</html>