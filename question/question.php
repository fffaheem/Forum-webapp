<?php

include "../partials/conn.php";

session_start();
$boolLoggedIn = false;
$fetchedQuestion="";
if(isset($_SESSION) and isset($_SESSION["username"])){
    $username = $_SESSION["username"];
    $sessionUsername = $_SESSION["username"];
    $boolLoggedIn = true;

    $sql = "SELECT * FROM allusers where `username` = '$username'";
    $result = $conn->query($sql);
    $data = $result->fetch_object();
    $loggedEmail = $data->{"email"};
    
}else{
    $boolLoggedIn = false;
}

$qSno="";

if( isset($_GET) and isset($_GET["ques"]) and isset($_GET["user"]) ){
    $qSno = $_GET["ques"];
    $qUsername = $_GET["user"];

    // getting likes Count 
    $sql = "SELECT * FROM `question_like` where `q_sno` = '$qSno'";
    $result = $conn->query($sql);
    $no_ofLikes = $conn->affected_rows;

    // checking if user has liked it or not
    if($boolLoggedIn){
        $likeFunc = "likeFunc(this,$qSno,`$qUsername`)";
        $sql = "SELECT * FROM question_like where `q_sno` = '$qSno' and `likedby` = '$username'";
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
        $likeFunc = "alertLike(this)";

    }

    $sql = "SELECT * FROM question where `q_sno` = '$qSno' and `username` = '$qUsername' ";
    $result = $conn->query($sql);
    $aff = $conn->affected_rows;
    if($aff>0){
        $boolQuestionExist = true;
    }else{
        $boolQuestionExist = false;
    }

    if($boolQuestionExist){
        $data = $result->fetch_object();

        $qSno = $data->{"q_sno"};
        $qUsername = $data->{"username"};
        $email = $data->{"email"};
        $qTitle = $data->{"title"};
        $qTitle = stripcslashes($qTitle);
        $qTitle = htmlspecialchars($qTitle);
        $qDesc = $data->{"titledesc"};
        $qDesc = stripcslashes($qDesc);
        $qDesc = htmlspecialchars($qDesc);
        $qCategory = $data->{"category"};
        $qTime = $data->{"time"}; 
        $newDate = date("j-F Y", strtotime($qTime));
        $newTime = date("l, g:i a", strtotime($qTime));

        // echo var_dump($data);

        if($boolLoggedIn){
            $textArea =  "<textarea class='form-control bg-dark text-white' id='answer' name='answer' rows='3' style='height: calc(30vh)' required></textarea> ";
            $button =  "<button type='submit' id='lol' class='btn btn-primary'>Answer</button> ";
        }else{
            $textArea =  "<div class='bg-light border border-2 border-secondary' id='answer' name='answer' style='height: 30vh; opacity: 15%;'></div>";
            $button =  "<div class='btn bg-primary text-white' style='opacity: 30%;'>Answer</div>
                        <div class='align-self-center mx-5' style='letter-spacing: 0.4rem;'> Log-in to Answer </div>";

        }

        if($boolLoggedIn){
            $quesAnchor = "<a href='../AllUsers/userProfile.php?user=$qUsername' class='links'> Asked by -  $qUsername || $email </a>";
        }else{
            $quesAnchor = "Asked by -  $qUsername || $email ";
        }

        $fetchedQuestion = "
        <div class='container bg-dark text-white py-4 px-5 my-5 border border-5 border-bottom-0 border-secondary rounded-3' id='question'>
                <div id='reportQues' onClick='reportQuesFunc($qSno,`$qUsername`,`$qCategory`)'> <i class='far fa-flag'></i> </div>
                <h3 style='letter-spacing: 0.75rem;'>$qTitle</h3>
                <div class='container my-3 text-secondary my-5'>
                    <p  class='my-0 lh-1 '> $quesAnchor </p>
                    <p class='my-0 lh-1 '>category - $qCategory</p>
                </div>
                <p class='spaceRetainer' style = 'word-break : break-all;'>$qDesc</p>
                <div class= 'd-flex justify-content-end align-items-center'>
                    <div class='d-flex flex-column align-items-center'> 
                        <i class='$likeClass fa-heart mx-3' onClick='$likeFunc'></i>
                        <small class='my-2'>$no_ofLikes</small>
                    </div>
                </div>
                <p class='text-secondary'>$newDate || $newTime</p>

                <hr class='my-5'>

                <div class='container mb-5' id='questionFormOut'>
                    <form id='questionForm'>
                        <div class='my-3 '>
                            <label for='answer' class='form-label fs-4 mx-2' style='letter-spacing: 0.2rem;'>Answer this question</label>
                            $textArea
                        </div>
                        <div class='d-flex flex-row'>
                            $button
                        </div>
                    </form>
                </div>
        </div>";
    }

}else{
    $boolQuestionExist = false;
}





$boolAnswerExist = false;


if($boolQuestionExist){
    $sql2 = "SELECT * FROM answers where `q_sno` = '$qSno' ORDER BY `answers`.`time` DESC";
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

            if($boolLoggedIn){
                $sql = "SELECT * FROM answer_like WHERE `a_sno` = '$aSno' and `q_sno` = '$qSno' and `likedby` = '$sessionUsername'";
                $result = $conn->query($sql);
                $boolUserHasLikedThis = $conn->affected_rows;
                // echo "||$aSno  q->$qSno||  $boolUserHasLikedThis <br>" ;
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
                <div class='m-3 '>
                    <label for='reply' class='form-label text-dark fs-4 mx-2' style='letter-spacing: 0.2rem;'>Add a Reply</label>
                    <textarea class='bg-dark form-control text-white' name='reply' rows='3' required></textarea>
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
            "<div class='card bg-secondary bg-gradient mb-4' id='$aUsername-$aSno' >
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
                <div class='container mt-2 mb-3'> 
                    <h3 class='mx-3'> Replies </h3>
                    <div class=' text-dark p-3'> $tables </div>
                </div>
                $replyInput
            </div>";



        }
    }
    else{
        $boolAnswerExist = false;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include "../partials/linkAndMeta.php"; ?>    
    <title>Question</title>
    <link rel="stylesheet" href="../static/question.css">
    <script defer src="./question.js"></script>
</head>
<body>
    <!-- navigation Bar -->
    <?php include "../partials/navbar.php" ?>

    <div class="container">

    <?php  

        if($boolQuestionExist){
            echo $fetchedQuestion; 
            if($boolAnswerExist){
                
            
                echo"<div class='container my-5'>
                        <hr>
                        <h2>Answers</h2>
                        <div class='container mb-5' id='sortOut'>
                            <div>
                                <label for='sort'>Sort By: </label>
                                <select class='form-select' id='sortBy' onchange='getCategory()' aria-label='Default select example' name='sortBy'>
                                    <option selected>New</option>         
                                    <option value='old'>Old</option>
                                    <option value='mostLiked'>Most Liked</option>
                                </select>
                            </div>
                        </div>

                        <div class='container my-5' id='answers'>

                            $fetchedAnswer


                        </div>

                    </div>";
            }
            else{
                echo"<div class='container my-5'>
                        <hr>
                        <h2>Answers</h2>
                        <div class='container my-5' id='answers'>
                        <h1> <span class='badge bg-secondary'>No, Answers Yet </span> </h1>

                        </div>

                    </div>";
            }
        }
        else{
            echo "<h1 class='my-5 text-center'>No such question </h1>";
        }

    ?>
</body>

</html>