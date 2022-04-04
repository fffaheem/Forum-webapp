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

if($boolLoggedIn){
    if(isset($_GET) and isset($_GET["del"]) and isset($_GET["deleteSno"])){
        $aSno = $_GET["deleteSno"];

        $sql = "SELECT * FROM answers where `a_sno` = '$aSno'";
        $res = $conn->query($sql);
        $data = $res->fetch_object();
        $username = $data->{"username"};
        $theAnswer = $data->{"answer"};
        $len = strlen($theAnswer);
        if($len>50){
            $title = substr($theAnswer,0,50);
            $theAnswer .= ".....";
        }
        $theAnswerNow = '[[ '.$theAnswer.' ]]';


        $sql = "DELETE from answers where `a_sno` = '$aSno'";
        $result = $conn->query($sql);
        if($result){

            $sql = "INSERT INTO notifications (`username`,`message`,`type`) VALUES ('$username','Your Answer $theAnswerNow was deleted by admin','profileUser')";
            $res = $conn->query($sql);

            echo "pass";
        }else{
            echo "fail";
        }
    }

    if(isset($_GET) and isset($_GET["sortBy"]) and isset($_GET["category"]) ){
        $sortBy = $_GET["sortBy"];
        $category = $_GET["category"];
        $fetchedAnswers = "";

        if($sortBy == "New"){
            if($category == "All"){
                $sql = "SELECT * FROM answers  ORDER BY `answers` . `time` DESC";
            }else{
                $sql = "SELECT * FROM answers where `qCategory` = '$category' ORDER BY `answers` . `time` DESC";
            }
        }elseif($sortBy == "report"){
            if($category == "All"){
                $sql = "SELECT answers.q_sno,answers.a_sno,answers.qCategory,answers.email,answers.username,answers.answer,answers.time,count(report_ans.a_sno)
                        FROM answers
                        left join report_ans
                        on answers.a_sno = report_ans.a_sno
                        group by answers.a_sno
                        order by count(report_ans.a_sno) desc;";
            }else{
                $sql = "SELECT answers.q_sno,answers.a_sno,answers.qCategory,answers.email,answers.username,answers.answer,answers.time,count(report_ans.a_sno)
                        FROM answers
                        left join report_ans
                        on answers.a_sno = report_ans.a_sno
                        group by answers.a_sno
                        having answers.qCategory = '$category'
                        order by count(report_ans.a_sno) desc;";
            }
        }elseif($sortBy = "likeCount"){
            if($category == "All"){
                $sql = "SELECT answers.a_sno, answers.q_sno, answers.qCategory, answers.email, answers.username, answers.answer, 
                        answers.time, answer_like.answeredby, answer_like.likedby, answer_like.time as aLikeTime, 
                        answer_like.like_sno,count(answer_like.q_sno)
                        FROM answers
                        LEFT JOIN answer_like
                        ON answers.a_sno= answer_like.a_sno
                        GROUP BY answers.a_sno
                        ORDER BY count(answer_like.q_sno) DESC;";
            }else{
                $sql = "SELECT answers.a_sno, answers.q_sno, answers.qCategory, answers.email, answers.username, answers.answer, 
                        answers.time, answer_like.answeredby, answer_like.likedby, answer_like.time as aLikeTime, 
                        answer_like.like_sno,count(answer_like.q_sno)
                        FROM answers
                        LEFT JOIN answer_like
                        ON answers.a_sno= answer_like.a_sno
                        GROUP BY answers.a_sno
                        having answers.qCategory = '$category'
                        ORDER BY count(answer_like.q_sno) DESC;";
            }
        }
        else{
            if($category == "All"){
                $sql = "SELECT * FROM answers ORDER BY `answers` . `time` ASC";
            }else{
                $sql = "SELECT * FROM answers where `qCategory` = '$category' ORDER BY `answers` . `time` ASC";
            }
        }

        $result = $conn->query($sql);
        $aff = $conn -> affected_rows;
        if($aff > 0){
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
    
                $fetchedAnswers .=
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
            $fetchedAnswers =  "<span class='badge bg-info'> <h4> No Answers Found</h4> </span>";
        }

        echo $fetchedAnswers;
    }

    

}

?>