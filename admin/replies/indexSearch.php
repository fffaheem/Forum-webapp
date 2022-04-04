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

    $boolReplyEmpty = true;
    $repliesArr = "";
    $sql = "SELECT * FROM replies WHERE `q_category` LIKE '%$searchQuery%' or  
                                    `email` LIKE '%$searchQuery%' or  
                                    `reply` LIKE '%$searchQuery%' or  
                                    `time` LIKE '%$searchQuery%'  
                                    order by `replies`.`time` desc";

    $result = $conn->query($sql);
    $aff = $conn->affected_rows;
    if($aff > 0){
        $boolReplyEmpty = false;
        while( $data = $result->fetch_object() ){
            $rSno = $data->{"r_sno"};
            $rSno = stripcslashes($rSno);
            $rSno = htmlspecialchars($rSno);

            $aSno = $data->{"a_sno"};
            $aSno = stripcslashes($aSno);
            $aSno = htmlspecialchars($aSno);

            $qSno = $data->{"q_sno"};
            $qSno = stripcslashes($qSno);
            $qSno = htmlspecialchars($qSno);

            $qCategory = $data->{"q_category"};
            $qCategory = stripcslashes($qCategory);
            $qCategory = htmlspecialchars($qCategory);

            $email = $data->{"email"};
            $email = stripcslashes($email);
            $email = htmlspecialchars($email);

            $rUsername = $data->{"username"};
            $rUsername = stripcslashes($rUsername);
            $rUsername = htmlspecialchars($rUsername);

            $reply = $data->{"reply"};
            $reply = stripcslashes($reply);
            $reply = htmlspecialchars($reply);

            $time = $data->{"time"};
            $newDate = date("j-F Y", strtotime($time));
            $newTime = date("l, g:i a", strtotime($time));

            // question detail
            $quesSql = "SELECT * FROM question WHERE `q_sno` = '$qSno'";
            $resultQuesSql = $conn->query($quesSql);
            $dataQues = $resultQuesSql->fetch_object();
            $quesAskUsername = $dataQues->{"username"};

            // Report Count Query 
            $sqlReport = "SELECT * FROM report_reply where `r_sno` = '$rSno'";
            $resultReport = $conn->query($sqlReport);
            $reportCount = $conn->affected_rows;
            //=================
            
            $repliesArr .= "
            <div class='card text-white bg-dark mb-3 replyCard my-4'>
                <div class='card-header d-flex  justify-content-between'>
                    <div> $qCategory </div>
                    <div> Report Count: $reportCount </div>
                </div>
                <div class='card-body'>
                    <h5 class='card-title'>By: $rUsername</h5>
                    <p class='card-text spaceRetain'>$reply</p>
                    <div class='d-flex justify-content-between'>
                        <p class='card-text'>$newTime || $newDate</p>
                        <div>
                            <a href='../../question/question.php?ques=$qSno&user=$quesAskUsername#rep-$rUsername-$rSno' class='btn btn-success'> Open </a> 
                            <button class='btn btn-danger' onClick='repliesDeleteFunc(this,$rSno)'> Delete </button>
                        </div>
                    </div>
                </div>
            </div>";
        }
    }else{
        $repliesArr = "<h1 class='text-center wrap text-wrap my-5'> <span class='badge bg-secondary'> No Result </span> </h1>";
    }

    $cancel = "<div class='container fs-3 d-flex justify-content-end' onclick='searchCancelFunc()'> <i class='fas fa-times cancelSearch'></i> </div>";
    echo $cancel . $repliesArr;
}

?>