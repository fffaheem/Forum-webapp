<?php

include "../../partials/conn.php";

$boolAdminLoggedIn = false;

session_start();
if(isset($_SESSION) and isset($_SESSION["adminLogIn"]) and isset($_SESSION["adminUsername"])){
    $sessionUsername = $_SESSION["adminUsername"];
    $boolAdminLoggedIn = true;
}else{
    header("location: ../index.php");

}

if($boolAdminLoggedIn){

    $boolFeedBackFound = false;

    $feedBackArr = "";
    $sql = "SELECT * FROM `contactus` ORDER BY `contactus`.`date` DESC";
    $result = $conn->query($sql);
    $aff = $conn->affected_rows;

    if($aff > 0){
        $boolFeedBackFound = true;
    
    
        while($data = $result->fetch_object()){
            $name = $data->{"name"};
            $name = stripcslashes($name);
            $name = htmlspecialchars($name);

            $email = $data->{"email"};
            $email = stripcslashes($email);
            $email = htmlspecialchars($email);

            $message = $data->{"message"};
            $message = stripcslashes($message);
            $message = htmlspecialchars($message);

            $date = $data->{"date"};
            $newDate = date("j-F Y", strtotime($date));
            $newTime = date("l, g:i a", strtotime($date));

            $feedBackArr .= "<div class='card my-4'>
                                <div class='card-header bg-info'>
                                    $email
                                </div>
                                <div class='card-body'>
                                    <h5 class='card-title'>$name</h5>
                                    <p class='card-text'>$message</p>
                                    <footer class='blockquote-footer'>$newTime || $newDate</footer>
                                </div>
                            </div>";
        }
    }else{

        $feedBackArr = "<span class='badge bg-info'>Empty, No Feedbacks yet</span>";
    }

}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include "../../partials/linkAndMeta.php"; ?>
    <link rel="stylesheet" href="../../static/adminNavbar.css">
    <script src="./index.js"></script>
    <title>Admin || Feedbacks</title>
</head>
<body>
    <?php
        require "../../partials/adminNavbar.php";
    ?>

    <?php
        require "../../partials/notification.php";
    ?>

    <div class="container my-4">
        <h3 class = "mb-4">Users Feedbacks</h3>
    
        <div class='container my-5 '>
            <div>
                <label for='sort'>Sort By: </label>
                <select class='form-select' aria-label='Default select example' onchange='getCategory()' name='sortBy' id='sortBy'>
                    <option selected>New</option>         
                    <option value='old'>Old</option>
                </select>
            </div>       
        </div>

        <div id="feedbackContainer">            
            <?php
            echo $feedBackArr
            ?>
        </div>

    </div>
</body>
</html>