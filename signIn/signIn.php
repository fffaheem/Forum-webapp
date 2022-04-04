<?php

// include "../partials/conn.php";

session_start();
if(isset($_SESSION) and isset($_SESSION["username"])){
    header("location: ../index.php");
}


$boolActivationMssg = false;
$boolActivatedMssg = false;

if(isset($_SESSION) and isset($_SESSION["message"])){
    $mssg = $_SESSION["message"];

    $boolActivationMssg = true;


    $activationMssg  = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                            <strong>Activation Required</strong> $mssg.
                            <button type='button' onClick='understoodBtn()' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>";
}


if(isset($_SESSION) and isset($_SESSION["activatedMessage"])){
    $mssg = $_SESSION["activatedMessage"];

    $boolActivatedMssg = true;


    $activatedMssg  = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                            <strong>Activated</strong> $mssg.
                            <button type='button' onClick='understoodBtn()' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>";
}


if(isset($_GET) and isset($_GET["ok"])){
    $understood = $_GET["ok"];

    if($understood){
        session_unset();
        session_destroy();
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <?php include "../partials/linkAndMeta.php"; ?>    
    <title>SignIn</title>
    
    <link rel="stylesheet" href="../static/signIn.css">
    <script defer src="./signIn.js"></script>
</head>
<body>
    <?php include "../partials/navbar.php" ?>

    <div class="container-sm my-5">
        <h1 class="text-center">Log In</h1>    

        <div class="my-5">
            <?php
            // activation mssg
            if($boolActivationMssg){
                echo $activationMssg;
            }
            if($boolActivatedMssg){
                echo $activatedMssg;
            }
            ?>
        </div>

        <div class="container my-5" id="logInFormOut">

            <form class="my-5" id="logInForm">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                    <small class="mx-2 form-text" id="usernameSmall">Enter your username</small>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                    <small class="mx-2 form-text" id="passwordSmall">Your password is safe with us.</small>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="showPassword">
                    <label class="form-check-label" for="showPassword">Show Password</label>
                </div>
                <button type="submit" id="logInBtn" class="btn btn-primary">log In</button>
            </form>

            <h4>Not a Member? <span class="badge bg-success"> <a class="btn text-light" href="../signUp/signUp.php" style="line-height: 0.5;" >Sign Up </a></span></h4> 
            <a class = 'btn link-primary' href="../forget/index.php" style="line-height: 0.5;" >Forgot password? </a>
        </div>

    </div>
</body>

</html>