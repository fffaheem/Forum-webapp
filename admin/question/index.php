<?php

include "../../partials/conn.php";
$boolLoggedIn = false;
session_start();
if(isset($_SESSION) and isset($_SESSION["adminLogIn"]) and isset($_SESSION["adminUsername"])){
    $sessionUsername = $_SESSION["adminUsername"];
    $boolLoggedIn = true;
}else{
    header("location: ../index.php");
}

if($boolLoggedIn){
    $questionArr = "";

    $sql = "SELECT * FROM `question` order by `question` . `time` desc";
    $result = $conn->query($sql);
    $aff = $conn -> affected_rows;
    if($aff > 0){
        while( $data = $result->fetch_object() ){
            $qSno = $data->{"q_sno"};
            $qSno = stripcslashes($qSno);
            $qSno = htmlspecialchars($qSno);

            $email = $data->{"email"};
            $email = stripcslashes($email);
            $email = htmlspecialchars($email);

            $username = $data->{"username"};
            $username = stripcslashes($username);
            $username = htmlspecialchars($username);

            $title = $data->{"title"};
            $title = stripcslashes($title);
            $title = htmlspecialchars($title);

            $titleDesc = $data->{"titledesc"};
            $titleDesc = stripcslashes($titleDesc);
            $titleDesc = htmlspecialchars($titleDesc);

            $category = $data->{"category"};
            $category = stripcslashes($category);
            $category = htmlspecialchars($category);

            $time = $data->{"time"};
            $newDate = date("j-F Y", strtotime($time));
            $newTime = date("l, g:i a", strtotime($time));

            //Getting Likes Count
            $likeSql = "SELECT * FROM question_like where `q_sno` = '$qSno'";
            $likeResult = $conn -> query($likeSql);
            $likesCount = $conn -> affected_rows;

            //Getting Report count
            $reportSql = "SELECT * FROM report_ques where `q_sno` = '$qSno'";
            $reportResult = $conn->query($reportSql);
            $reportCount = $conn->affected_rows;

            $questionArr .=
            "
            <div class='card my-4 bg-dark text-white quesCard'>

                <div class='card-header d-flex  justify-content-between'>
                    <div> Asked by: $username </div>
                    <div> Report Count: $reportCount </div>
                </div>
                <div class='card-header d-flex  justify-content-between'>
                    <div> Category: $category </div>
                    <div> likes Count: $likesCount </div>
                </div>

                <div class='card-body'>
                    <h5 class='card-title'>$title</h5>
                    <p class='card-text spaceRetain'>$titleDesc</p>
                    <div class='d-flex justify-content-between'>
                        <p class='card-text'>$newTime || $newDate</p>
                        <div>
                            <a href='../../question/question.php?ques=$qSno&user=$username' class='btn btn-success'> Open </a> 
                            <button class='btn btn-danger' onClick='quesDeleteFunc(this,$qSno)'> Delete </button>
                        </div>
                    </div>
                </div>
            </div>";
        }
    }else{
        $questionArr =  "<span class='badge bg-info'> <h4> No Question Found</h4> </span>";
    }

    $sno = 1;
    include "../../partials/categories.php"; 
    $sortingForm = "
    <div class='container my-5 flex-row'>
        <div class='row gx-5'>
            <div class='col'>
                <label for='sort'>Sort By: </label>
                <select class='form-select' aria-label='Default select example' onchange='getCategory()' name='sortBy' id='sortBy'>
                    <option selected>New</option>         
                    <option value='old'>Old</option>
                    <option value='likeCount'>likes Count</option>
                    <option value='report'>Report Count</option>
                </select>
            </div>

            <div class='col'>                
                <label for='sort'>Category: </label>
                <select class='form-select' aria-label='Default select example' onchange='getCategory()' name='category' id='category'>
                    <option selected>All</option>
                    $options
                </select>
            </div>
        </div>        
    </div>";
    
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include "../../partials/linkAndMeta.php"; ?>
    <link rel="stylesheet" href="../../static/adminNavbar.css">
    <link rel="stylesheet" href="../../static/utility.css">
    <script defer src="./index.js"></script>
    <title>Admin || All Questions</title>
</head>
<body>

    <?php
        require "../../partials/adminNavbar.php";
    ?>

    <?php
        require "../../partials/notification.php";
    ?>

    <section>
        <div id= "sortingForm">
           <?php
                echo $sortingForm;
           ?>
        </div>
        <div class="container my-5" id="questionContainer">
            <?php
                echo $questionArr;
            ?>
        </div>
    </section>

</body>
</html>