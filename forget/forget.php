<?php

include "../partials/conn.php";

session_start();
if(isset($_SESSION) and isset($_SESSION["username"])){
    header("location: ../signIn/signIn.php");
}

$boolEnterNewPassword = false;

if(isset($_GET["token"]) and isset($_GET["email"]) ){

    $boolEmailFound = false;

    $token = $_GET["token"];
    $email = $_GET["email"];

    if($token == "0" || $token == ""){
        header("location: ../signIn/signIn.php");
    }

    $sql = "SELECT * FROM allusers WHERE `email` = '$email'";
    $res = $conn->query($sql);
    if($res){
        $aff = $conn->affected_rows;
        if($aff == 1){
            $boolEmailFound = true;
        }
    }

    if($boolEmailFound){
        $data = $res->fetch_object();
        $tokenDatabase =  $data->{"token"};

        if($tokenDatabase == $token){
            
            $boolEnterNewPassword = true;

        }else{

            header("location: ../signIn/signIn.php");
        }
    }else{
        header("location: ../signIn/signIn.php");
    }
    
    // echo "$email <br> $token";
}else{

    header("location: ../signIn/signIn.php");

}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <?php include "../partials/linkAndMeta.php"; ?>    
    <title>Password Reset</title>
    
    <link rel="stylesheet" href="../static/signIn.css">
    <script defer src="./forgot.js"></script>
    
    <style>
        body{
            position: absolute;
            height: 100%;
            width: 100%;
        }

        .outer{
            position: relative;
            height: 100%;
            width: 100%;
            padding: 0 0.5rem;
            display: flex;
            justify-content: center;
            align-items: center;

        }

        .outer > div{
            border: 2px solid rebeccapurple;
            padding: 2rem 0.5rem;
            border-radius: 1rem;

        }


        @media only screen and (min-width: 767px) {
            .outer > div{
                width: 60%;
            }
        }
        @media only screen and (min-width: 900px) {
            .outer > div{
                width: 50%;
            }
        }
    </style>
</head>
<body>
    
    <div class="outer">
        <div class="container-sm my-5">
            <h1 class="text-center">Password Change</h1>    
            <div id="loaderContainerDiv"></div>

            <div class="container my-5 " id="newPasswordFormOut">

                <form class="my-5" id="newPasswordForm">
                    <div class="mb-3">
                        <label for="username" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <small class="mx-2 form-text" id="usernameSmall">Enter your new Password</small>
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                        <small class="mx-2 form-text" id="usernameSmall">Enter your new password again</small>
                    </div>
                    <button type="submit" id="logInBtn" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
