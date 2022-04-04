<?php
include "../partials/conn.php";

session_start();
$boolLoggedIn = false;
if( isset($_SESSION) and isset($_SESSION["username"]) ){
  $sessionUsername = $_SESSION["username"];
  $boolLoggedIn = true;

}else{
  $boolLoggedIn = false;
  header("location: ../index.php");

}

$boolAnswerExist = false;
$sql = "SELECT * FROM answers WHERE `username` = '$sessionUsername' order by `answers`.`time` DESC";
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
}

// ----------------Sorting Form --------------

$sno = 1;
include "../partials/categories.php";

$categories = " 
<div class='col'>
    <label for='sort'>Category: </label>
    <select class='form-select' id='category' onchange='getCategory()' aria-label='Default select example' name='sort'>
    <option selected>All</option>
      $options
    </select>
</div>";


$sortByForm = "
<div class='container my-5 flex-row'>
  <div class='row gx-5'>

    <div class='col'>
      <label for='sort'>Sort By: </label>
      <select class='form-select' id='sortBy' onchange='getCategory()' aria-label='Default select example' name='sort'>
        <option selected>New</option>
        <option value='old'>Old</option>
        <option value='mostLiked'>Most Liked</option>
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
  <title>Profile || My Answers</title>

  <link rel="stylesheet" href="../static/commonProfile.css">
  <link rel="stylesheet" href="../static/profileMyAnswers.css">
  <link rel="stylesheet" href="../static/utility.css">

  <!-- new mini navbar -->
  <link rel="stylesheet" href="../static/miniNavBar.css">

  <script defer src="./myAnswer.js"></script>


</head>

<body>

  <!-- navigation Bar -->
  <?php include "../partials/navbar.php"; ?>
  
  <!-- Mini navBar -->
  <?php include "../partials/miniNavbar.php" ?>

  <!-- notification -->
  <?php include "../partials/notification.php" ?>
  
  <!-- Sorting Form -->
  <?php 
    if($boolAnswerExist){

      echo $sortByForm; 
    }
  ?>



  <div class="container-sm my-5">

    <h1>My Answers</h1>
      <div class="container" id="myAns">
        <?php
          if($boolAnswerExist){
            echo $fetchedAnswer;
          }else{
            echo "<div class='container my-5'> <h1><span class='badge bg-dark text-wrap'>No Answers, Yet</span></h1></div>";
          }
        ?>
      </div>
  </div>

</body>

</html>