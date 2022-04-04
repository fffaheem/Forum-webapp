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
    <title>forget Password</title>
    
    <link rel="stylesheet" href="../static/signIn.css">
    <script defer src="./index.js"></script>

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
    <div class="outer" >
        <div class="container-sm my-5">
            <h1 class="text-center">Forgot Password</h1> 

            <div id="loaderContainerDiv"></div>

            <div class="container my-5" id="forgetFormOut">

                <form class="my-5" id="forgetForm">
                    <div class="mb-3">
                        <label for="username" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                        <small class="mx-2 form-text" id="usernameSmall">Enter your Email</small>
                    </div>
                    <button type="submit" id="logInBtn" class="btn btn-primary">Submit</button>
                </form>
            </div>

        </div>
    </div>
</body>

</html>