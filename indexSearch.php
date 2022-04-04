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


if(isset($_GET) and isset($_GET["search"])){
    $searchQuery = $_GET["search"];
    $searchQuery = $conn->real_escape_string($searchQuery);

    $sql = 
    "SELECT *  FROM question
    where username LIKE '%$searchQuery%' OR
        email LIKE '%$searchQuery%' OR
        title LIKE '%$searchQuery%' OR
        titledesc LIKE '%$searchQuery%' OR
        category LIKE '%$searchQuery%' OR
        time LIKE '%$searchQuery%'
    ORDER BY question . time DESC;
    ";

    $result = $conn->query($sql);
    $allQuestions = "";
    if($result){
        $aff = $conn->affected_rows;
        if($aff > 0){
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
                  // iff not logged in
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
                $email = stripcslashes($email);
                $email = htmlspecialchars($email);
                $qTitle = $data->{"title"};
                $qTitle = stripcslashes($qTitle);
                $qTitle = htmlspecialchars($qTitle);
                $qDesc = $data->{"titledesc"};
                $qDesc = stripcslashes($qDesc);
                $qDesc = htmlspecialchars($qDesc);
                $qCategory = $data->{"category"};
                $qCategory = stripcslashes($qCategory);
                $qCategory = htmlspecialchars($qCategory);
                $qTime = $data->{"time"}; 
                $qTime = stripcslashes($qTime);
                $qTime = htmlspecialchars($qTime);
                $newDate = date("j-F Y", strtotime($qTime));
                $newTime = date("l, g:i a", strtotime($qTime));

                if($boolLoggedIn){
                  $quesAnchor = "<a class='link' href='./AllUsers/userProfile.php?user=$qUsername'> $qUsername || $email </a>";
                }else{
                  $quesAnchor = " $qUsername || $email ";
                }
                
                $allQuestions .= "
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
        }else{
            $allQuestions = "<h1 class='text-center wrap text-wrap my-5'> <span class='badge bg-secondary'> No Result </span> </h1>";
        }
    }else{
        $allQuestions = "<h1 class='text-center wrap text-wrap my-5'> <span class='badge bg-secondary'> No Result </span> </h1>";

    }

    $cancel = "<div class='container fs-3 d-flex justify-content-end' onclick='searchCancelFunc()'> <i class='fas fa-times cancelSearch'></i> </div>";
    echo $cancel . $allQuestions;


}

?>