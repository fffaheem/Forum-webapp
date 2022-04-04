<?php
session_start();
include "../partials/conn.php";
$boolLoggedIn = false;
if(isset($_SESSION) and isset($_SESSION["username"])){
  $sessionUsername = $_SESSION["username"];
  $boolLoggedIn = true;

}else{
  header("location: ../index.php");
}

if($boolLoggedIn){
    if(isset($_GET) and isset($_GET["sortBy"]) and isset($_GET["category"]) ){
        $answerArr = "";
        $sortBy = $_GET["sortBy"];
        $category = $_GET["category"];

        if($category == "All"){
            if($sortBy=="new"){
                $sql = 
                "SELECT answers.a_sno, answers.q_sno, answers.qCategory, answers.email, answers.answer, answers.time as aTime, answer_like.answeredby, answer_like.likedby, answer_like.time as aLikeTime, answer_like.like_sno
                FROM answers 
                inner JOIN answer_like 
                ON answers.a_sno= answer_like.a_sno
                HAVING answer_like.likedby = '$sessionUsername'  
                ORDER BY `answers`.`time` DESC;";
            }
            else if($sortBy=="old"){
                $sql = 
                "SELECT answers.a_sno, answers.q_sno, answers.qCategory, answers.email, answers.answer, answers.time as aTime, answer_like.answeredby, answer_like.likedby, answer_like.time as aLikeTime, answer_like.like_sno
                FROM answers 
                inner JOIN answer_like 
                ON answers.a_sno= answer_like.a_sno
                HAVING answer_like.likedby = '$sessionUsername'  
                ORDER BY `answers`.`time` ASC;";
            }
            else if($sortBy=="oldLiked"){
                $sql = 
                "SELECT answers.a_sno, answers.q_sno, answers.qCategory, answers.email, answers.answer, answers.time as aTime, answer_like.answeredby, answer_like.likedby, answer_like.time as aLikeTime, answer_like.like_sno
                FROM answers 
                inner JOIN answer_like 
                ON answers.a_sno= answer_like.a_sno
                HAVING answer_like.likedby = '$sessionUsername'  
                ORDER BY `answer_like`.`time` ASC;";
            }
            else if($sortBy=="Recently Liked"){
                $sql = 
                "SELECT answers.a_sno, answers.q_sno, answers.qCategory, answers.email, answers.answer, answers.time as aTime, answer_like.answeredby, answer_like.likedby, answer_like.time as aLikeTime, answer_like.like_sno
                FROM answers 
                inner JOIN answer_like 
                ON answers.a_sno= answer_like.a_sno
                HAVING answer_like.likedby = '$sessionUsername'  
                ORDER BY `answer_like`.`time` DESC;";
            }
            
        }else{
            if($sortBy=="new"){
                $sql = 
                "SELECT answers.a_sno, answers.q_sno, answers.qCategory, answers.email, answers.answer, answers.time as aTime, answer_like.answeredby, answer_like.likedby, answer_like.time as aLikeTime, answer_like.like_sno
                FROM answers 
                inner JOIN answer_like 
                ON answers.a_sno= answer_like.a_sno
                HAVING answer_like.likedby = '$sessionUsername' and `answers`.`qCategory` = '$category'
                ORDER BY `answers`.`time` DESC;";
            }
            else if($sortBy=="old"){
                $sql = 
                "SELECT answers.a_sno, answers.q_sno, answers.qCategory, answers.email, answers.answer, answers.time as aTime, answer_like.answeredby, answer_like.likedby, answer_like.time as aLikeTime, answer_like.like_sno
                FROM answers 
                inner JOIN answer_like 
                ON answers.a_sno= answer_like.a_sno
                HAVING answer_like.likedby = '$sessionUsername'and `answers`.`qCategory` = '$category'  
                ORDER BY `answers`.`time` ASC;";
            }
            else if($sortBy=="oldLiked"){
                $sql = 
                "SELECT answers.a_sno, answers.q_sno, answers.qCategory, answers.email, answers.answer, answers.time as aTime, answer_like.answeredby, answer_like.likedby, answer_like.time as aLikeTime, answer_like.like_sno
                FROM answers 
                inner JOIN answer_like 
                ON answers.a_sno= answer_like.a_sno
                HAVING answer_like.likedby = '$sessionUsername' and `answers`.`qCategory` = '$category' 
                ORDER BY `answer_like`.`time` ASC;";
            }
            else if($sortBy=="Recently Liked"){
                $sql = 
                "SELECT answers.a_sno, answers.q_sno, answers.qCategory, answers.email, answers.answer, answers.time as aTime, answer_like.answeredby, answer_like.likedby, answer_like.time as aLikeTime, answer_like.like_sno
                FROM answers 
                inner JOIN answer_like 
                ON answers.a_sno= answer_like.a_sno
                HAVING answer_like.likedby = '$sessionUsername' and `answers`.`qCategory` = '$category' 
                ORDER BY `answer_like`.`time` DESC;";
            }

        }
        $boolLikedQuesExist = false;
        $result = $conn->query($sql);
        $aff = $conn->affected_rows;
        if($aff > 0){
           
            $boolLikedQuesExist = true;
            while($data = $result->fetch_object()){
                $likeSno = $data->{"like_sno"};
                $likeSno = stripcslashes($likeSno);
                $likeSno = htmlspecialchars($likeSno);
                $qSno = $data->{"q_sno"};
                $qSno = stripcslashes($qSno);
                $qSno = htmlspecialchars($qSno);
                $aSno = $data->{"a_sno"};
                $aSno = stripcslashes($aSno);
                $aSno = htmlspecialchars($aSno);
                $qCat = $data->{"qCategory"};
                $qCat = stripcslashes($qCat);
                $qCat = htmlspecialchars($qCat);
                $aEmail = $data->{"email"};
                $aEmail = stripcslashes($aEmail);
                $aEmail = htmlspecialchars($aEmail);
                $aAnswer = $data->{"answer"};
                $aAnswer = stripcslashes($aAnswer);
                $aAnswer = htmlspecialchars($aAnswer);
                $aTime = $data->{"aTime"}; 
                $aTime = stripcslashes($aTime);
                $aTime = htmlspecialchars($aTime);
                $answeredby = $data->{"answeredby"};
                $answeredby = stripcslashes($answeredby);
                $answeredby = htmlspecialchars($answeredby);
                $likedby = $data->{"likedby"};
                $likedby = stripcslashes($likedby);
                $likedby = htmlspecialchars($likedby);
                $aLikeTime = $data->{"aLikeTime"};
                $aLikeTime = stripcslashes($aLikeTime);
                $aLikeTime = htmlspecialchars($aLikeTime);
                $newAnsDate = date("j-F Y", strtotime($aLikeTime));
                $newAnsTime = date("l, g:i a", strtotime($aLikeTime));
          
                  //getting no_ofLikes 
                  $likeSql = "SELECT * FROM answer_like WHERE `a_sno` ='$aSno';" ;
                  $likeResult = $conn->query($likeSql);
                  $no_ofLikes = $conn->affected_rows;
          
                  // getting ques detail 
                  $quesSql = "SELECT * FROM question WHERE `q_sno` = '$qSno'";
                  $quesResult = $conn->query($quesSql);
                  $quesData = $quesResult->fetch_object();
                  $quesAskedBy = $quesData->{"username"};
          
                  // ofcourse user has liked it 
          
          
                $likeClass = "fas";


                $answerArr .= "
                <div class='card my-5 bg-dark bg-gradient text-white'>
                <div class='card-header'>
                    <a href='../AllUsers/userProfile.php?user=$answeredby' class='links'> $answeredby || $aEmail </a>
                </div>
                <div class='card-body'>
                    <figcaption class='blockquote-footer text-secondary'>
                    Category: <cite title='Source Title'>$qCat</cite>
                    </figcaption>
                    <p class='card-text spaceRetainer'>$aAnswer</p>
                    <div class= 'd-flex justify-content-between align-items-center'>
                        <a href='../question/question.php?ques=$qSno&user=$quesAskedBy#$answeredby-$aSno' class='btn btn-dark'>Open</a>
                        <div class='d-flex flex-column align-items-center'> 
                            <i class='$likeClass fa-heart mx-3' onClick='ansLikeFunc(this,$qSno,$aSno,`$answeredby`)' ></i>
                            <small class='my-2'>$no_ofLikes</small>
                        </div>
                    </div>
                </div>
                <div class='card-footer text-secondary'>
                    $newAnsDate || $newAnsDate
                </div>
                </div>";

            }
        }else{
            $answerArr = "<h1 class='my-5'> <span class='badge bg-secondary text-wrap'>No Result </span> </h1>";
        }

        echo $answerArr;
    }


}





?>