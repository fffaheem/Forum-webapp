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

if(isset($_GET) and isset($_GET["sortBy"]) and isset($_GET["category"])){
    $sortBy = $_GET["sortBy"];
    $category = $_GET["category"];


    $boolAnswerExist = false;
    if($sortBy == "old"){
        if($category=="All"){
            $sql = "SELECT * FROM answers WHERE `username` = '$sessionUsername' order by `answers`.`time` ASC";
        }else{
            $sql = "SELECT * FROM answers WHERE `username` = '$sessionUsername' and `qCategory` = '$category' order by `answers`.`time` ASC";
        }
    }else if($sortBy == "New"){
        if($category=="All"){
            $sql = "SELECT * FROM answers WHERE `username` = '$sessionUsername' order by `answers`.`time` DESC";
        }else{
            $sql = "SELECT * FROM answers WHERE `username` = '$sessionUsername' and `qCategory` = '$category' order by `answers`.`time` DESC";
        }

    }elseif($sortBy == "mostLiked"){
        if($category=="All"){
            $sql = "SELECT answers.a_sno, answers.q_sno, answers.qCategory, answers.email, answers.username, answers.answer, 
            answers.time, answer_like.answeredby, answer_like.likedby, answer_like.time as aLikeTime, 
            answer_like.like_sno,count(answer_like.q_sno)
            FROM answers
            LEFT JOIN answer_like
            ON answers.a_sno= answer_like.a_sno
            GROUP BY answers.a_sno
            having answers.username = '$sessionUsername'
            ORDER BY count(answer_like.q_sno) DESC;";
        }else{
            $sql = "SELECT answers.a_sno, answers.q_sno, answers.qCategory, answers.email, answers.username, answers.answer, 
            answers.time, answer_like.answeredby, answer_like.likedby, answer_like.time as aLikeTime, 
            answer_like.like_sno,count(answer_like.a_sno)
            FROM answers
            LEFT JOIN answer_like
            ON answers.a_sno = answer_like.a_sno
            GROUP BY answers.a_sno
            having answers.username = '$sessionUsername' and answers.qCategory = '$category'
            ORDER BY count(answer_like.a_sno) DESC;";
        }
    }

    $result = $conn->query($sql);
    $no_ofAnswer = $conn->affected_rows;
    if($no_ofAnswer > 0){
        $boolAnswerExist = true;
    }

    if($boolAnswerExist){
 
        $fetchedAnswer = "";
        while($data = $result->fetch_object()){
          $aSno = $data->{"a_sno"};
          $aSno = stripcslashes($aSno);
          $aSno = htmlspecialchars($aSno);
          $qSno = $data->{"q_sno"};
          $qSno = stripcslashes($qSno);
          $qSno = htmlspecialchars($qSno);
          $qCategory = $data->{"qCategory"};
          $qCategory = stripcslashes($qCategory);
          $qCategory = htmlspecialchars($qCategory);
          $aEmail = $data->{"email"};
          $aEmail = stripcslashes($aEmail);
          $aEmail = htmlspecialchars($aEmail);
          $aUser = $data->{"username"};
          $aUser = stripcslashes($aUser);
          $aUser = htmlspecialchars($aUser);
          $aAnswer = $data->{"answer"};
          $aAnswer = stripcslashes($aAnswer);
          $aAnswer = htmlspecialchars($aAnswer);
          $aTime = $data->{"time"};
          $aTime = stripcslashes($aTime);
          $aTime = htmlspecialchars($aTime);
          $newAnsDate = date("j-F Y", strtotime($aTime));
          $newAnsTime = date("l, g:i a", strtotime($aTime));
      
          $ansLikeFunc = "ansLikeFunc(this,$qSno,$aSno,`$aUser`)";
      
          // getting question detail
          $quesSql = "SELECT * FROM question WHERE `q_sno` = '$qSno'";
          $resultQuesSql = $conn->query($quesSql);
          $dataQues = $resultQuesSql->fetch_object();
          $quesAskUsername = $dataQues->{"username"};
          // ---------------------------------------
      
          // now checking if user has liked it or not 
      
          $likeSql = "SELECT * FROM answer_like where `a_sno` = '$aSno' and `q_sno` = '$qSno' and `likedby` = '$sessionUsername'";
          $resultLikeSql = $conn->query($likeSql);
          $affLikeSql = $conn->affected_rows;
          if($affLikeSql==1){
            $boolUserHasLikedIt = true;
          }
          else{
            $boolUserHasLikedIt = false;
          }
      
          if($boolUserHasLikedIt){        
            $likeClass = "fas";
          }else{
            $likeClass = "far";
          }
      
          // getting no of likes
          $no_ofLikes = 0;
          $likeCountSql = "SELECT * FROM answer_like WHERE `a_sno` = '$aSno' and `q_sno` = '$qSno';";
          $resultCount = $conn->query($likeCountSql);
          $no_ofLikes = $conn->affected_rows;
      
      
          $fetchedAnswer .= "
          <div class='card bg-dark text-white my-4'>
            <h5 class='card-header'>$aUser || $aEmail</h5>
            <div class='card-body'>
              <p class='card-text spaceRetainer'>$aAnswer</p>
              <a href='../question/question.php?ques=$qSno&user=$quesAskUsername#$aUser-$aSno'> <button class='btn btn-secondary me-2'> open </button> </a>
              <div class='d-flex justify-content-between align-items-center'>
                <small class='card-text text-secondary'>$newAnsDate || $newAnsTime</small>
                <div class='d-flex flex-row'>
                  <button class='btn btn-primary me-2 editBtn'> Edit </button>
                  <button class='btn btn-danger me-2' onClick='delFunc($qSno,$aSno,`$sessionUsername`)'> Delete </button>
                  <div class='d-flex flex-column align-items-center justify-content-center'> 
                    <i class='$likeClass fa-heart mx-3' onClick='$ansLikeFunc'></i>
                    <small class='my-2'>$no_ofLikes</small>
                  </div>
                </div>
              </div>
            </div>
          </div>";
      
        }
    }else{
        $fetchedAnswer = "<h2 class='my-5 mx-2'> <span class='badge bg-secondary text-wrap'> No result </span> </h2> ";
    }

    echo $fetchedAnswer;
}

?>