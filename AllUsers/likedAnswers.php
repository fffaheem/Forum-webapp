<?php

include "../partials/conn.php";
session_start();
$boolLoggedin = false;
if(isset($_SESSION) and isset($_SESSION["username"]) ){
  $sessionUsername = $_SESSION["username"];
  $boolLoggedin = true;

}else{
  header("location: ../index.php");

}

if($boolLoggedin){
    $boolIsPrivate = "";
    if(isset($_GET) and isset($_GET["user"])){
        $user = $_GET["user"];
        $sql = "SELECT * FROM allusers where `username` = '$user'";
        $result = $conn->query($sql);
        if($result){
            $aff = $conn->affected_rows;
            if($aff == 1){
                $data = $result->fetch_object();
                $showProfile = $data->{"show_profile"};

                if($showProfile == "private"){
                    $boolIsPrivate = true;
                }else{
                    $boolIsPrivate = false;
                }
            }else{
                header("location: ./userProfile.php?user=$user");

            }
        }

        if($boolIsPrivate){
            header("location: ./userProfile.php?user=$user");
        }
    }else{
        header("location: ./userProfile.php?user=$user");

    }
}


$boolLikedAnsExist = false;
$fetchedAnswer = "";
$sql = 
"SELECT answers.a_sno, answers.q_sno, answers.qCategory, answers.email, answers.answer, answers.time as aTime, answer_like.answeredby, answer_like.likedby, answer_like.time as aLikeTime, answer_like.like_sno
FROM answers 
inner JOIN answer_like 
ON answers.a_sno= answer_like.a_sno
HAVING answer_like.likedby = '$user'  
ORDER BY `answer_like`.`time` DESC;";
$result = $conn->query($sql);
$aff = $conn->affected_rows;
if($aff > 0){
    $answerArr = "";
    $boolLikedAnsExist = true;
    while($data = $result->fetch_object()){
      $boolUserHasLikedIt = false;

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

}


// ----------------Sorting Form --------------

$sno = 1;
include "../partials/categories.php";

$categories = " 
<div class='col'>
    <label for='sort'>Category: </label>
    <select class='form-select' id='category' onchange='getCategory(`$user`)'  aria-label='Default select example' name='sort'>
    <option selected>All</option>
      $options
    </select>
</div>";


$sortByForm = "
<div class='container my-5 flex-row'>
  <div class='row gx-5'>

    <div class='col'>
      <label for='sort'>Sort By: </label>
      <select class='form-select' id='sortBy' onchange='getCategory(`$user`)' aria-label='Default select example' name='sort'>
        <option selected>Recently Liked</option>
        <option value='oldLiked'>Oldest Liked</option>
        <option value='new'>New</option>
        <option value='old'>Old</option>
      </select>
    </div>
    
    $categories
    

  </div>

</div>";
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <?php include "../partials/linkAndMeta.php"; ?>    
  <title>User || Liked Answers</title>

  <link rel="stylesheet" href="../static/commonProfile.css">
  <link rel="stylesheet" href="../static/utility.css">

  <script defer src="./likedAnswers.js"></script>

  <!-- new mini navbar -->
  <link rel="stylesheet" href="../static/miniNavBar.css">
  <link rel="stylesheet" href="../static/miniUserNavbar.css">
  <link rel="stylesheet" href="../static/profile.css">
  <link rel="stylesheet" href="../static/userProfile.css">

</head>
<body>
    <!-- navigation Bar -->
    <?php   include "../partials/navbar.php";  ?>

    <!-- Mini navBar -->
    <?php include "../partials/miniUserNavbar.php"; ?>  

    <!-- notification -->
    <?php include "../partials/notification.php" ?>

      <!-- Sorting Form -->
    <?php 
        if($boolLikedAnsExist){
            echo $sortByForm; 
        }
    ?>

    <div class="container-sm my-5">

        <?php
            $username = $_GET["user"];
            echo "<h3> $username's Liked Answers </h3>";
        ?>

        <div class="container" id="myLikedAnswer">
            <?php

                if($boolLikedAnsExist){
                echo $answerArr;
                }
                else{
                echo "
                <div class='container my-5'>
                    <h1> <span class='badge bg-dark text-wrap'> It's All Empty, No Liked Answers </span> </h1>
                </div>";  
                }

            ?>
        </div>

    </div>
</body>
</html>