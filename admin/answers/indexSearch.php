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

    $boolAnswerEmpty = true;
    $answerArr = "";
    $sql = "SELECT * FROM answers WHERE `qCategory` LIKE '%$searchQuery%' or 
                                        `email` LIKE '%$searchQuery%' or 
                                        `username` LIKE '%$searchQuery%' or 
                                        `answer` LIKE '%$searchQuery%' or 
                                        `time` LIKE '%$searchQuery%'
                                        order by `answers`.`time` DESC";

    $result = $conn->query($sql);
    $aff = $conn->affected_rows;
    if($aff > 0){
        $boolAnswerEmpty = false;
        while( $data = $result->fetch_object() ){
            $qSno = $data->{"q_sno"};
            $qSno = stripcslashes($qSno);
            $qSno = htmlspecialchars($qSno);

            $aSno = $data->{"a_sno"};
            $aSno = stripcslashes($aSno);
            $aSno = htmlspecialchars($aSno);

            $qCategory = $data->{"qCategory"};
            $qCategory = stripcslashes($qCategory);
            $qCategory = htmlspecialchars($qCategory);

            $email = $data->{"email"};
            $email = stripcslashes($email);
            $email = htmlspecialchars($email);

            $username = $data->{"username"};
            $username = stripcslashes($username);
            $username = htmlspecialchars($username);

            $answer = $data->{"answer"};
            $answer = stripcslashes($answer);
            $answer = htmlspecialchars($answer);

            $time = $data->{"time"};
            $newDate = date("j-F Y", strtotime($time));
            $newTime = date("l, g:i a", strtotime($time));

            //Getting Likes Count
            $likeSql = "SELECT * FROM answer_like where `a_sno` = '$aSno'";
            $likeResult = $conn -> query($likeSql);
            $likesCount = $conn -> affected_rows;

            //Getting Report count
            $reportSql = "SELECT * FROM report_ans where `a_sno` = '$aSno'";
            $reportResult = $conn->query($reportSql);
            $reportCount = $conn->affected_rows;

            //Question Detail
            $quesSql = "SELECT * FROM question WHERE `q_sno` = '$qSno'";
            $resultQuesSql = $conn->query($quesSql);
            $dataQues = $resultQuesSql->fetch_object();
            $quesAskUsername = $dataQues->{"username"};

            $answerArr .=
            "
            <div class='card my-4 bg-secondary text-dark ansCard'>

                <div class='card-header d-flex  justify-content-between'>
                    <div> Answered by: $username </div>
                    <div> Report Count: $reportCount </div>
                </div>
                <div class='card-header d-flex  justify-content-between'>
                    <div> Category: $qCategory </div>
                    <div> likes Count: $likesCount </div>
                </div>

                <div class='card-body'>
                    <p class='card-text spaceRetain'>$answer</p>
                    <div class='d-flex justify-content-between'>
                        <p class='card-text'>$newTime || $newDate</p>
                        <div>
                            <a href='../../question/question.php?ques=$qSno&user=$quesAskUsername#$username-$aSno' class='btn btn-success'> Open </a> 
                            <button class='btn btn-danger' onClick='ansDeleteFunc(this,$aSno)'> Delete </button>
                        </div>
                    </div>
                </div>
            </div>";
        }
    }else{
        $answerArr = "<h1 class='text-center wrap text-wrap my-5'> <span class='badge bg-secondary'> No Result </span> </h1>";
    }

    $cancel = "<div class='container fs-3 d-flex justify-content-end' onclick='searchCancelFunc()'> <i class='fas fa-times cancelSearch'></i> </div>";
    echo $cancel . $answerArr;
}

?>