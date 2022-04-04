<?php
include "../partials/conn.php";
$boolLoggedIn = false;

$qSno = $_GET["ques"];
    
$sql = "SELECT * FROM question WHERE `q_sno` = '$qSno'";
$result = $conn->query($sql);
$data = $result->fetch_object();
$qCategory = $data->{"category"};
$quesUsername = $data->{"username"};


session_start();
if(isset($_SESSION) and isset($_SESSION["username"])){
    $boolLoggedIn = true;
    $username = $_SESSION["username"];
    $sessionUsername = $_SESSION["username"];

    $sql = "SELECT * FROM allusers where `username` = '$username'";
    $result = $conn->query($sql);
    $data = $result->fetch_object();
    $email = $data->{"email"};
    $loggedEmail = $data->{"email"};
    
    
    
}else{
    // header("location: ../index.php");
    $boolLoggedIn = false;
}

// echo $qSno;
// echo $email;

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $answer = $_POST["answer"];
    $answer = $conn->real_escape_string($answer);

    $sql = "INSERT INTO answers (`q_sno`,`qCategory`,`email`,`username`,`answer`) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issss",$qSno,$qCategory,$email,$username,$answer);
    $result = $stmt->execute();

    if($result){
        $sql = "SELECT * FROM answers WHERE `q_sno` = '$qSno' and `email` = '$email' and `username` = '$sessionUsername' and `answer` = '$answer'";
        $res = $conn->query($sql);
        $data = $res->fetch_object();
        $aSno = $data->{"a_sno"};
        if($quesUsername != $sessionUsername){
            $sql = "INSERT INTO notifications (`username`,`q_sno`,`a_sno`,`res_username`,`type`) VALUES('$quesUsername','$qSno','$aSno','$sessionUsername','questionAnswered')";
            $res = $conn->query($sql);
        }

        $alertClass = "success";
        $alertInnerStrong = "Success";
        $alertInnerSpan = "Your Answer has been submitted";
        $alertMssg = "<div class='alert alert-$alertClass alert-dismissible fade show' id='success' role='alert'>
                        <strong>$alertInnerStrong</strong> $alertInnerSpan
                        <button type='button' onClick='outcomeBtn()' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
        echo $alertMssg;
    }
    else{
        $alertClass = "danger";
        $alertInnerStrong = "Unsuccessfull";
        $alertInnerSpan = "Answer not posted try Again";
        $alertMssg = "<div class='alert alert-$alertClass alert-dismissible fade show' id='fail' role='alert'>
                        <strong>$alertInnerStrong</strong> $alertInnerSpan
                        <button type='button' onClick='outcomeBtn()' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
        echo $alertMssg;
    }
}

if( isset($_GET) and isset($_GET["ques"]) and isset($_GET["user"]) and !($_SERVER["REQUEST_METHOD"] == "POST") ){
    $boolAnswerExist = false;

    if(isset($_GET["sortBy"])){
        $sort = $_GET["sortBy"];

        if($sort == "New"){
            $sql2 = "SELECT * FROM answers where `q_sno` = '$qSno' ORDER BY `answers`.`time` DESC";

        }
        else if($sort == "old"){
            $sql2 = "SELECT * FROM answers where `q_sno` = '$qSno' ORDER BY `answers`.`time` ASC";
        }
        else{
            $sql2 = "SELECT answers.q_sno, answers.a_sno, answers.qCategory, answers.email, answers.username, answers.answer,answers.time,count(answer_like.a_sno)
            FROM answers
            LEFT JOIN answer_like
            ON answers.a_sno= answer_like.a_sno
            GROUP BY answers.a_sno
            having answers.q_sno = '$qSno'
            ORDER BY count(answer_like.a_sno) DESC;";
            
            
        }


    }else{
        $sql2 = "SELECT * FROM answers where `q_sno` = '$qSno' ORDER BY `answers`.`time` DESC";
    }
    
    $result2 = $conn->query($sql2);
    $aff = $conn->affected_rows;

    if($aff>0){
        $boolAnswerExist = true;
        $fetchedAnswer = "";
        while($data = $result2->fetch_object()){
            $aSno = $data->{"a_sno"};

            // getting no. of Likes
            $sql = "SELECT * FROM answer_like WHERE `a_sno` = '$aSno'";
            $result = $conn->query($sql);
            $no_ofLikes = $conn->affected_rows;

            //checking if user has liked it or not
            if($boolLoggedIn){
                $sql = "SELECT * FROM answer_like WHERE `a_sno` = '$aSno' and `q_sno` = '$qSno' and `likedby` = '$sessionUsername'";
                $result = $conn->query($sql);
                $boolUserHasLikedThis = $conn->affected_rows;
                
                if($boolUserHasLikedThis > 0){
                    $likeClass = "fas";
                  }
                  else{
                    $likeClass = "far";
                  }

            }else{
                $likeClass = "far";
            }


            $answer = $data->{"answer"};
            $answer = stripcslashes($answer);
            $answer = htmlspecialchars($answer);
            $aUsername = $data->{"username"};
            $email = $data->{"email"};
            $time = $data->{"time"};
            $newAnsDate = date("j-F Y", strtotime($time));
            $newAnsTime = date("l, g:i a", strtotime($time));


            if($boolLoggedIn){
                $ansLikeFunc = "ansLikeFunc(this,$aSno,`$aUsername`)";
                $replyInput = "
                <div class='m-3'>
                    <label for='reply' class='form-label text-dark fs-4 mx-2' style='letter-spacing: 0.2rem;'>Add a Reply</label>
                    <textarea class='bg-dark form-control  text-white' name='reply' rows='3' required></textarea>
                    <button class='btn btn-dark my-2' onClick='addReplyFunc(this,`${qSno}`,`${aSno}`,`${sessionUsername}`,`${loggedEmail}`,`${qCategory}`)'> Reply </button>
                </div>";
            }else{
                $ansLikeFunc = "alertAnsLike(this)";
                $replyInput = "";
            }

            // for replies  
            // the variables replies take is qSno and aSno and gives replies

            include "../reply/reply.php";

            // --------------------------------------------------

            if($boolLoggedIn){
                $ansAnchor = "<a href = '../AllUsers/userProfile.php?user=$aUsername' class='links'> Answered by: $aUsername || $email </a>";
            }else{
                $ansAnchor = "Answered by: $aUsername || $email ";
            }

            $fetchedAnswer .= 
            "<div class='card bg-secondary bg-gradient mb-4'>
                <div id='reportAns' onClick='reportAnsFunc(this,$qSno,$aSno,`$aUsername`,`$qCategory`)'> <i class='far fa-flag'></i> </div>
                <div class='card-header text-wrap ans-head'>
                    $ansAnchor
                </div>
                <div class='card-body '>
                    <blockquote class='blockquote mb-0'>
                        <p class='answerContent spaceRetainer'>$answer</p>
                        <div class='d-flex justify-content-between align-items-center'>
                            <footer class='blockquote-footer text-dark answerContent'>Answered on:  <cite title='Source Title'>$newAnsDate || $newAnsTime</cite></footer>
                            <div class='d-flex'>
                                <div class='d-flex flex-column align-items-center justify-content-center'> 
                                    <i class='$likeClass fa-heart mx-3' onClick='$ansLikeFunc'></i>
                                    <small class='my-2'>$no_ofLikes</small>
                                </div>
                            </div>
                        </div>

                    </blockquote>
                </div>
                <div class='container mt-3 mb-3'> 
                    <h3 class='mx-3'> Replies </h3>
                    <div class=' text-dark p-3'> $tables </div>
                </div>
                $replyInput
            </div>";



        }
    }
    
    if(!$boolAnswerExist){
        $fetchedAnswer = "fail";
        echo $fetchedAnswer;
    }
    else{
        echo $fetchedAnswer;
    }
}



?>