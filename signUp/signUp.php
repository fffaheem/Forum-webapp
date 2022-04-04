<?php

session_start();
if(isset($_SESSION) and isset($_SESSION["username"])){
    header("location: ../index.php");
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include "../partials/linkAndMeta.php"; ?>    
    <title>SignUp</title>
    
    <link rel="stylesheet" href="../static/signUp.css">
    <script defer src="./signUp.js"></script>
</head>
<body>
    <?php include "../partials/navbar.php" ?>

    <div class="container-sm my-4">
        <h1 class="text-center">Sign Up</h1>

        <div class="container my-3" id="signUpFormOut" >

           

            <form class="my-5 row gx-5" id="signUpForm">
                <div class="mb-3 col">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                    <small id="fullNameSmall"></small>
                </div>
                <div class="mb-3 col">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                    <small id="usernameSmall"></small>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email"  required>
                    <small id="emailSmall"></small>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password"  required>
                    <small id="passwordSmall"></small>
                </div>
                <div class="mb-3">
                    <label for="confirmPassword" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                    <small id="confirmPasswordSmall"></small>
                </div>
                <div class="mb-3">
                    <input type="checkbox" class="form-check-input" id="showPassword">
                    <label class="form-check-label" for="showPassword">Show Password</label>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary" id="signUp">Sign up</button>
                </div>
            </form>

            <h4>Already a Member? <span class="badge bg-success"> <a class="btn text-light" href="../signIn/signIn.php" style="line-height: 0.5;"> Log In</a></span></h4> 
        </div>

    </div>
</body>


</html>