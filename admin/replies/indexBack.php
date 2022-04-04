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
    if(isset($_GET) and isset($_GET["del"]) and isset($_GET["replieSno"])){
        $rSno = $_GET["replieSno"];

        $sql = "SELECT * FROM replies where `r_sno` = '$rSno'";
        $res = $conn->query($sql);
        $data = $res->fetch_object();
        $username = $data->{"username"};
        $theReply = $data->{"reply"};
        $len = strlen($theReply);
        if($len>35){
            $title = substr($theReply,0,35);
            $theReply .= ".....";
        }
        $theReplyNow = '[[ '.$theReply.' ]]';

        $sql = "DELETE from replies where `r_sno` = '$rSno'";
        $result = $conn->query($sql);
        if($result){

            $sql = "INSERT INTO notifications (`username`,`message`,`type`) VALUES ('$username','Your Reply $theReplyNow was deleted by admin','profileUser')";
            $res = $conn->query($sql);

            echo "pass";
        }else{
            echo "fail";

        }
    }


    if(isset($_GET) and isset($_GET["sortBy"]) and isset($_GET["category"]) ){
        $sortBy = $_GET["sortBy"];
        $category = $_GET["category"];
        $fetchedReplies = "";
        if($sortBy == "New"){
            if($category == "All"){
                $sql = "SELECT * FROM replies  ORDER BY `replies` . `time` DESC";
            }else{
                $sql = "SELECT * FROM replies where `q_category` = '$category' ORDER BY `replies` . `time` DESC";
            }
        }elseif($sortBy == "report"){
            if($category == "All"){
                $sql = "SELECT replies.r_sno,replies.q_sno,replies.a_sno,replies.email,replies.username,replies.reply,replies.time,replies.q_category,count(report_reply.r_sno) 
                        FROM replies
                        left join report_reply 
                        on replies.r_sno = report_reply.r_sno
                        group by replies.r_sno 
                        order by count(report_reply.r_sno) desc;";
            }else{
                $sql = "SELECT replies.r_sno,replies.q_sno,replies.a_sno,replies.email,replies.username,replies.reply,replies.time,replies.q_category,count(report_reply.r_sno) 
                        FROM replies
                        left join report_reply 
                        on replies.r_sno = report_reply.r_sno
                        group by replies.r_sno 
                        having replies.q_category = '$category'
                        order by count(report_reply.r_sno) desc;";
            }
        }else{
            if($category == "All"){
                $sql = "SELECT * FROM replies ORDER BY `replies` . `time` ASC";
            }else{
                $sql = "SELECT * FROM replies where `q_category` = '$category' ORDER BY `replies` . `time` ASC";
            }
        }

        $result = $conn->query($sql);
        $aff = $conn -> affected_rows;
        if($aff>1){
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
                
                $fetchedReplies .= "
                <div class='card text-white bg-dark mb-3 replyCard'>
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
            $fetchedReplies =  "<span class='badge bg-info'> <h4> No Replies Founds </h4> </span>";
        }

        echo $fetchedReplies;
    }

    

}

?>