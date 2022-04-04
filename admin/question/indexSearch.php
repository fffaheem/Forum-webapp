<?php

$boolLoggedIn = false;

include "../../partials/conn.php";
session_start();
if(isset($_SESSION) and isset($_SESSION["adminLogIn"]) and isset($_SESSION["adminUsername"])){
    $sessionUsername = $_SESSION["adminUsername"];
    $boolLoggedIn = true;
}else{
    header("location: ../index.php");
}

if(isset($_GET) and isset($_GET["search"]) and $boolLoggedIn){
    $searchQuery = $_GET["search"];
    $searchQuery = $conn->real_escape_string($searchQuery);

    $boolQuestionEmpty = true;
    $fetchedQuestion = "";
    $sql = "SELECT * FROM question WHERE `email` LIKE '%$searchQuery%' OR
                                        `title` LIKE '%$searchQuery%' OR 
                                        `titledesc` LIKE '%$searchQuery%' OR 
                                        `category` LIKE '%$searchQuery%' OR 
                                        `time` LIKE '%$searchQuery%' 
                                        ORDER BY `question`.`time` DESC";

    $result = $conn->query($sql);
    $aff = $conn->affected_rows;
    if($aff > 0){
        $boolQuestionEmpty = false;
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
            $reportCount = $conn-> affected_rows;

            $fetchedQuestion .=
            "
            <div class='card my-4 bg-dark text-white quesCard'>

                <div class='card-header d-flex  justify-content-between'>
                    <div> Asked by: $username </div>
                    <div> Report Count: $reportCount </div>
                </div>
                <div class='card-header d-flex  justify-content-between'>
                    <div> Category: $category </div>
                    <div> Likes Count: $likesCount </div>
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
        $fetchedQuestion = "<h1 class='text-center wrap text-wrap my-5'> <span class='badge bg-secondary'> No Result </span> </h1>";
    }

    $cancel = "<div class='container fs-3 d-flex justify-content-end' onclick='searchCancelFunc()'> <i class='fas fa-times cancelSearch'></i> </div>";
    echo $cancel . $fetchedQuestion;
}

?>