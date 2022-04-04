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
        $qSno = $_GET["deleteSno"];

        $sql = "SELECT * FROM question where `q_sno` = '$qSno'";
        $res = $conn->query($sql);
        $data = $res->fetch_object();
        $username = $data->{"username"};
        $title = $data->{"title"};
        $len = strlen($title);
        if($len>50){
            $title = substr($title,0,50);
            $title .= ".....";
        }
        $titleQues = '[[ '.$title.' ]]';

        $sql = "DELETE from question where `q_sno` = '$qSno'";
        $result = $conn->query($sql);
        if($result){

            $sql = "INSERT INTO notifications (`username`,`message`,`type`) VALUES ('$username','Your Question having title: $titleQues was deleted by admin','profileUser')";
            $res = $conn->query($sql);

            echo "pass";
        }else{
            echo "fail";
        }
    }

    if(isset($_GET) and isset($_GET["sortBy"]) and isset($_GET["category"]) ){
        $sortBy = $_GET["sortBy"];
        $category = $_GET["category"];
        $fetchedQuestion = "";

        if($sortBy == "New"){
            if($category == "All"){
                $sql = "SELECT * FROM question  ORDER BY `question` . `time` DESC";
            }else{
                $sql = "SELECT * FROM question where `category` = '$category' ORDER BY `question` . `time` DESC";
            }
        }elseif($sortBy == "report"){
            if($category == "All"){
                $sql = "SELECT question.q_sno,question.email,question.username,question.title,question.titledesc,question.category,question.time,count(report_ques.q_sno)
                        FROM question
                        left join report_ques
                        on question.q_sno =  report_ques.q_sno
                        group by question.q_sno
                        order by count(report_ques.q_sno) desc;";
            }else{
                $sql = "SELECT question.q_sno,question.email,question.username,question.title,question.titledesc,question.category,question.time,count(report_ques.q_sno)
                        FROM question
                        left join report_ques
                        on question.q_sno =  report_ques.q_sno
                        group by question.q_sno
                        having question.category = '$category'
                        order by count(report_ques.q_sno) desc;";
            }
        }elseif($sortBy = "likeCount"){
            if($category == "All"){
                $sql = "SELECT question.q_sno, question.email, question.username, question.title, question.titledesc, question.category, question.time,count(question_like.q_sno)
                        FROM question
                        LEFT JOIN question_like
                        ON question.q_sno= question_like.q_sno
                        GROUP BY question.q_sno
                        ORDER BY count(question_like.q_sno) DESC;";
            }else{
                $sql = "SELECT question.q_sno, question.email, question.username, question.title, question.titledesc, question.category, question.time,count(question_like.q_sno)
                        FROM question
                        LEFT JOIN question_like
                        ON question.q_sno= question_like.q_sno
                        GROUP BY question.q_sno
                        having question.category = '$category'
                        ORDER BY count(question_like.q_sno) DESC;";
            }
        }
        else{
            if($category == "All"){
                $sql = "SELECT * FROM question ORDER BY `question` . `time` ASC";
            }else{
                $sql = "SELECT * FROM question where `category` = '$category' ORDER BY `question` . `time` ASC";
            }
        }

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
            $fetchedQuestion =  "<span class='badge bg-info'> <h4> No Question Found</h4> </span>";
        }

        echo $fetchedQuestion;
    }

    

}

?>