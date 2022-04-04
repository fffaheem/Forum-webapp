<?php
session_start();
include "./partials/conn.php";

$boolLoggedIn = false;
$boolQuestionEmpty = true;

if(isset($_SESSION) and isset($_SESSION["username"])){
    $username = $_SESSION["username"];
    $boolLoggedIn = true;

}
else{
    $boolLoggedIn = false;
}





if($boolLoggedIn){
    

    if( isset($_GET) and isset($_GET["like"]) and isset($_GET["id"]) and isset($_GET["askedBy"])  ){
        $questionSno = $_GET["id"];
        $qAskedBy = $_GET["askedBy"];
        // echo $questionSno;

        $boolUserHasLikedThis = false;

        $sql = "SELECT * FROM question_like WHERE `q_sno` = '$questionSno' and `likedBy` = '$username' ";
        $result1 = $conn->query($sql);
        $aff = $conn->affected_rows;

        // $questionSno = (int)$questionSno;

        if($aff == 1){
            $boolUserHasLikedThis = true;
        }

        if(!$boolUserHasLikedThis){
            $sql = "INSERT INTO question_like (`q_sno`,`askedby`,`likedby`) Values (?,?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iss",$questionSno,$qAskedBy,$username);
            $result = $stmt->execute();

            if($qAskedBy != $username){
              $sql = "SELECT * FROM `question_like` where `q_sno` = '$questionSno' and `askedby` = '$qAskedBy' and `likedby` = '$username' ";
              $res = $conn->query($sql);
              $data = $res->fetch_object();
              $qLikeSno = $data->{"like_sno"};
              
              $sql = "INSERT INTO notifications (`username`,`q_sno`,`q_like_sno`,`res_username`,`type`) VALUES ('$qAskedBy','$questionSno','$qLikeSno','$username','questionLiked')";
              $res = $conn->query($sql);
            }

            echo "success";
        }
        else{
            $sql = "DELETE FROM question_like WHERE `q_sno`= '$questionSno' and `likedby` = '$username' ";
            $conn->query($sql);
            echo "fail";
        }
    }

}




if(isset($_GET) and isset($_GET["sortBy"]) and isset($_GET["category"])){
    $sortBy = $_GET["sortBy"];
    $category = $_GET["category"];

    // $sql = '';
    if($sortBy == "New"){

        if($category=="All"){
            $sql = "SELECT * FROM question  ORDER BY `question`.`time` DESC";
            
        }else{
            
            $sql = "SELECT * FROM question WHERE `category` ='$category' ORDER BY `question`.`time` DESC";
        }
    }
    else if($sortBy == "old"){

        if($category=="All"){
            $sql = "SELECT * FROM question ORDER BY `question`.`time` ASC";
            
        }else{
            
            $sql = "SELECT * FROM question WHERE `category` ='$category' ORDER BY `question`.`time` ASC";
        }
    }
    else if($sortBy == "mostLiked"){
        if($category=="All"){
            // $sql = "SELECT *,COUNT(ql.q_sno) FROM question q,question_like ql where q.q_sno = ql.q_sno GROUP by ql.q_sno ORDER BY count(ql.q_sno) DESC;";
            $sql = "SELECT question.q_sno, question.email, question.username, question.title, question.titledesc, question.category, question.time,count(question_like.q_sno)
                    FROM question
                    LEFT JOIN question_like
                    ON question.q_sno= question_like.q_sno
                    GROUP BY question.q_sno
                    ORDER BY count(question_like.q_sno) DESC;";
            
        }else{            
            // $sql = "SELECT *,COUNT(ql.q_sno) FROM question q,question_like ql where q.q_sno = ql.q_sno and q.category ='$category' GROUP by ql.q_sno ORDER BY count(ql.q_sno) DESC;";
            $sql = "SELECT question.q_sno, question.email, question.username, question.title, question.titledesc, question.category, question.time,count(question_like.q_sno)
                    FROM question 
                    LEFT JOIN question_like
                    ON question.q_sno= question_like.q_sno
                    GROUP BY question.q_sno
                    HAVING question.category = '$category'
                    ORDER BY count(question_like.q_sno) DESC;";
        }
    }

   
    $questionArr = "";

  $result = $conn->query($sql);
  $aff = $conn->affected_rows;
  if($aff>0){

    $boolQuestionEmpty = false;
    
    while($data=$result->fetch_object()){

      $boolUserHasLikedThis = false;
      $qSno = $data->{"q_sno"};
      $qUsername = $data->{"username"};

      // fetching number of likes
      $sql1 = "SELECT * FROM question_like WHERE `q_sno` = '$qSno' ";
      $result1 = $conn->query($sql1);
      $no_ofLikes = $conn->affected_rows;      

      // fetching if user has liked it or not
      if($boolLoggedIn){
        $sql2 = "SELECT * FROM question_like WHERE `q_sno` = '$qSno' and `likedBy` = '$username' ";
        $result1 = $conn->query($sql2);
        $aff2 = $conn->affected_rows;
        if($aff2==1){
          $boolUserHasLikedThis = true;
        }
        
        if($boolUserHasLikedThis){
          $likeClass = "fas";
        }
        else{
          $likeClass = "far";
        }


        $likeFunc = "onClick='likeFunc(this,$qSno,`$qUsername`)'";
      }
      else{
        $likeClass = "far";
        $likeFunc = "onClick='alertLike(this)'";
      }


      // fetching top Liked Answer 

      $innerSql = "SELECT answers.q_sno, answers.a_sno, answers.qCategory, answers.email, answers.username, answers.answer,answers.time,count(answer_like.a_sno)
      FROM answers
      LEFT JOIN answer_like
      ON answers.a_sno = answer_like.a_sno
      GROUP BY answers.a_sno
      having answers.q_sno = '$qSno'
      ORDER BY count(answer_like.a_sno) DESC";



      $innerRes = $conn->query($innerSql);
      $innerAff = $conn->affected_rows;
      // echo $innerAff;
      if($innerAff > 0 ){

        $innerData = $innerRes->fetch_object();
        $ansSno = $innerData->{"a_sno"};
        $ansSno = stripcslashes($ansSno);
        $ansSno = htmlspecialchars($ansSno);
        $ansUsername = $innerData->{"username"};
        $ansUsername = stripcslashes($ansUsername);
        $ansUsername = htmlspecialchars($ansUsername);
        $ansEmail = $innerData->{"email"};
        $ansEmail = stripcslashes($ansEmail);
        $ansEmail = htmlspecialchars($ansEmail);
        $ansAnswer = $innerData->{"answer"};
        $ansAnswer = stripcslashes($ansAnswer);
        $ansAnswer = htmlspecialchars($ansAnswer);
        $ansTime = $innerData->{"time"};
        $ansTime = stripcslashes($ansTime);
        $ansTime = htmlspecialchars($ansTime);
        $newAnsDate = date("j-F Y", strtotime($ansTime));
        $newAnsTime = date("l, g:i a", strtotime($ansTime));
        $ansLikes = $innerData->{"count(answer_like.a_sno)"};
        $ansLikes = stripcslashes($ansLikes);
        $ansLikes = htmlspecialchars($ansLikes);

        // If answer has been Liked by User

        if($boolLoggedIn){
          $ansLikeFunc = "ansLikeFunc(this,`$qSno`,`$ansSno`,`$ansUsername`)";
          $innerSql = "SELECT * FROM answer_like where `a_sno` = '$ansSno' and `likedby` = '$username'";
          $innerRes = $conn->query($innerSql);
          $boolUserHasLikedAns = $conn->affected_rows;                
          if($boolUserHasLikedAns > 0){
              $ansLikeClass = "fas";
          }
          else{
            $ansLikeClass = "far";
          }
        }else{
          $ansLikeClass = "far";
          $ansLikeFunc = "alertAnsLike(this)";
        }

        if($boolLoggedIn){
          $ansAnchor = "<a class='link' href='./AllUsers/userProfile.php?user=$ansUsername'> Answered by: $ansUsername || $ansEmail </a>";
        }else{
          $ansAnchor = "Answered by: $ansUsername || $ansEmail";
        }

        $ans =       
        "<div class='card bg-secondary bg-gradient mb-4'>
            <div class='card-header'>
              $ansAnchor
            </div>
            <div class='card-body '>
                <blockquote class='blockquote mb-0'>
                    <p class='spaceRetain'>$ansAnswer</p>

                    <div class='d-flex justify-content-between align-items-center'>
                        <footer class='blockquote-footer text-dark'>Answered on:  <cite title='Source Title'>$newAnsDate || $newAnsTime</cite></footer>
                        <div class='d-flex fs-5'>
                          <div class='d-flex flex-column justify-content-end align-items-center'> 
                              <i class='$ansLikeClass fa-heart mx-3' onClick='$ansLikeFunc'></i>
                              <small class='my-2'>$ansLikes</small>
                          </div>
                        </div>
                    </div>

                </blockquote>
            </div>
        </div>";
      }
      else{
        $ans = "<span class='badge bg-info'>Empty, No Answers yet</span>";
      }


      // ---------------

      $email = $data->{"email"};
      $qTitle = $data->{"title"};
      $qTitle = stripcslashes($qTitle);
      $qTitle = strip_tags($qTitle);
      $qDesc = $data->{"titledesc"};
      $qDesc = stripcslashes($qDesc);
      $qDesc = htmlspecialchars($qDesc);
      $qCategory = $data->{"category"};
      $qTime = $data->{"time"};   
      $newDate = date("j-F Y", strtotime($qTime));
      $newTime = date("l, g:i a", strtotime($qTime));

      if($boolLoggedIn){
        $quesAnchor = "<a class='link' href='./AllUsers/userProfile.php?user=$qUsername'> $qUsername || $email </a>";
      }else{
        $quesAnchor = " $qUsername || $email ";
      }

      $questionArr .= "
      <div class='card my-5 bg-dark text-light'>
        <div class='card-header'>
          $quesAnchor
      </div>
      <div class='card-body'>
          <figcaption class='blockquote-footer mt-1'>
            Category: <cite title='Source Title'>$qCategory</cite>
          </figcaption>
        <h5 class='card-title'>$qTitle</h5>
        <p class='card-text spaceRetain'>$qDesc</p>
        <div class='d-flex justify-content-end'>
          <div style=display:none> </div>
          <div class='d-flex flex-column align-items-center'> 
            <i class='$likeClass fa-heart mx-3' $likeFunc></i>
            <small class='my-2'>$no_ofLikes</small>
          </div>
        </div>

        <!-- Top Answer Card  -->
        <div class='container my-4'>
            <hr>
            <h4 class='my-3'>Top Answer</h4>
            $ans
        </div>
        <a href='./question/question.php?ques=$qSno&user=$qUsername' class='btn btn-secondary'>Open Question</a>
        </div>
        <div class='card-footer text-muted'>
          $newDate || $newTime
        </div>
      </div>";

    }
  }
  else{
        $questionArr = "<h2 class='my-5 mx-2'> <span class='badge bg-secondary'> No result </span> </h2> ";
    }
    
    
    echo $questionArr;


    // echo $sortBy;

}

?>