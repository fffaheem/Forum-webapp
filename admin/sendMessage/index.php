<?php

include "../../partials/conn.php";
$boolLoggedIn = false;
session_start();
if(isset($_SESSION) and isset($_SESSION["adminLogIn"]) and isset($_SESSION["adminUsername"]) and  isset($_SESSION["username"])){
    $sessionUsername = $_SESSION["adminUsername"];
    $boolLoggedIn = true;
}else{
    header("location: ../index.php");
}


if($boolLoggedIn){



  
}
  
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include "../../partials/linkAndMeta.php"; ?>
    <link rel="stylesheet" href="../../static/adminNavbar.css">
    <link rel="stylesheet" href="../../static/utility.css">
    <link rel="stylesheet" href="../../static/sendAlluser.css">
    <script defer src="./index.js"></script>
    <title>Admin || Send Message</title>
</head>
<body>
    
    <dialog id="myModal">
        
    </dialog>


    <?php
        require "../../partials/adminNavbar.php";
    ?>

    <?php
        require "../../partials/notification.php";
    ?>
    
    <div class="container my-4">
        <h2>Send Message</h2>
    </div>
    <div class="container">
        <div class="form-floating">
            <textarea class="form-control" placeholder="Leave your message here" id="adminMessage" name="adminMessage" style="height: 60vh"></textarea>
            <label for="adminMessage">Message</label>
        </div>

        <div class='my-2 d-flex justify-content-end'>
            <button class="btn btn-warning mx-2" id="sendAll">Send to All</button>
            <button class="btn btn-primary mx-2" id="sendOne">Send to one</button>
        </div>

    </div> 


</body>
</html>