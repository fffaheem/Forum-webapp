<?php
session_start();
include "../partials/conn.php";

if(isset($_SESSION) and isset($_SESSION["username"])){
  $sessionUsername = $_SESSION["username"];

}else{
  header("location: ../index.php");
}

$boolNotificationEmpty = true;
$notificationArr = "";
$sql = "SELECT * FROM `notifications` WHERE `username` = '$sessionUsername' ORDER BY `notifications`.`time` DESC";
$result = $conn->query($sql);
$aff = $conn->affected_rows;
if($aff > 0){
  $boolNotificationEmpty = false;
}else{
  $boolNotificationEmpty = true;
}

if(!$boolNotificationEmpty){

  while( $data = $result->fetch_object() ){
    $sno = $data->{"s_no"};
    $username = $data->{"username"};
    $username = stripcslashes($username);
    $username = htmlspecialchars($username);
    $qSno = $data->{"q_sno"};
    $aSno = $data->{"a_sno"};
    $rSno = $data->{"r_sno"};
    $responseUsername = $data->{"res_username"};
    $message = $data->{"message"};
    $message = stripcslashes($message);
    $message = htmlspecialchars($message);
    $mssgType = $data->{"type"};
    $mssgType = stripcslashes($mssgType);
    $mssgType = htmlspecialchars($mssgType);
    $time = $data->{"time"};
    $newDate = date("j-F Y", strtotime($time));
    $newTime = date("l, g:i a", strtotime($time));

    if($qSno != NULL || $qSno != ""){
      $innerSql = "SELECT * FROM `question` where `q_sno` = '$qSno'";
      $innerRes = $conn->query($innerSql);
      $innerData = $innerRes->fetch_object();
      $quesUsername = $innerData->{"username"};
    }

    $body = "";
    if($mssgType == "profileUser"){
      $body = "<p class ='spaceRetainer'>$message</p>";
    }

    if($mssgType == "assignedAsAdmin"){
      $body = "<p class ='spaceRetainer'>You recently were assigned as Admin by <a class='link-info' href='../AllUsers/userProfile.php?user=$responseUsername'>$responseUsername</a> </p>";
    }

    if($mssgType == "makeAdmin"){
      $body = "<p class ='spaceRetainer'>You recently made <a class='link-info' href='../AllUsers/userProfile.php?user=$responseUsername'>$responseUsername</a> as Admin </p>";
    }

    if($mssgType == "removeAdmin"){
      $body = "<p class ='spaceRetainer'>You recently removed <a class='link-info' href='../AllUsers/userProfile.php?user=$responseUsername'>$responseUsername</a> from Admin </p>";
    }

    if($mssgType == "removeBeingAdmin"){
      $body = "<p class ='spaceRetainer'>You were removed as admin by  <a class='link-info' href='../AllUsers/userProfile.php?user=$responseUsername'>$responseUsername</a> </p>";
    }

    if($mssgType == "makeSuperAdmin"){
      $body = "<p class ='spaceRetainer'>You recently assigned <a class='link-info' href='../AllUsers/userProfile.php?user=$responseUsername'>$responseUsername</a> as Super Admin </p>";
    }

    if($mssgType == "assignAsSuperAdmin"){
      $body = "<p class ='spaceRetainer'>You were assigned as Super Admin by <a class='link-info' href='../AllUsers/userProfile.php?user=$responseUsername'>$responseUsername</a>. <small>** Please, be respectfull. </small> </p>";
    }

    if($mssgType == "broadcast"){
      $body = "<p class ='spaceRetainer'> $message </p>";
    }
    
    if($mssgType == "questionAnswered"){
      $body = "<p class ='spaceRetainer'> <a class='link-info' href='../AllUsers/userProfile.php?user=$responseUsername'>$responseUsername</a>  answered your <a class='link-info' href='../question/question.php?ques=$qSno&user=$quesUsername#$responseUsername-$aSno'>question</a> </p>";
    }

    if($mssgType == "answerReplied"){
      $body = "<p class ='spaceRetainer'> <a class='link-info' href='../AllUsers/userProfile.php?user=$responseUsername'>$responseUsername</a>  Replied to your <a class='link-info' href='../question/question.php?ques=$qSno&user=$quesUsername#rep-$responseUsername-$rSno'>answer</a> </p>";
    }

    if($mssgType == "questionLiked"){
      $body = "<p class ='spaceRetainer'> <a class='link-info' href='../AllUsers/userProfile.php?user=$responseUsername'>$responseUsername</a>  liked  your <a class='link-info' href='../question/question.php?ques=$qSno&user=$quesUsername'>question</a> </p>";
    }

    if($mssgType == "answerLiked"){
      $body = "<p class ='spaceRetainer'> <a class='link-info' href='../AllUsers/userProfile.php?user=$responseUsername'>$responseUsername</a>  liked  your <a class='link-info' href='../question/question.php?ques=$qSno&user=$quesUsername#$username-$aSno'>answer</a> </p>";
    }

    if($mssgType == "reportQues"){
      $body = "<p class ='spaceRetainer'> <a class='link-info' href='../AllUsers/userProfile.php?user=$responseUsername'>$responseUsername</a>  reported  your <a class='link-info' href='../question/question.php?ques=$qSno&user=$quesUsername'>question</a> </p>";
    }

    if($mssgType == "reportAns"){
      $body = "<p class ='spaceRetainer'> <a class='link-info' href='../AllUsers/userProfile.php?user=$responseUsername'>$responseUsername</a>  reported  your <a class='link-info' href='../question/question.php?ques=$qSno&user=$quesUsername#$username-$aSno'>answer</a> </p>";
    }

    if($mssgType == "reportReply"){
      $body = "<p class ='spaceRetainer'> <a class='link-info' href='../AllUsers/userProfile.php?user=$responseUsername'>$responseUsername</a>  reported  your <a class='link-info' href='../question/question.php?ques=$qSno&user=$quesUsername#rep-$username-$rSno'>reply</a> </p>";
    }


    if($mssgType == ""){
      $body = "<p class ='spaceRetainer'>$responseUsername Liked your question</p>";
    }

    $notificationArr .= "
      <div class='card my-1 bg-secondary bg-gradient'>
      <div class='card-header'>
        $newDate || $newTime
        <div class='cancelCorner'> 
          <i class='fas fa-times cancelSearch' onClick='specificDelete(this,`$sno`)'></i>
        </div>
      </div>
        <div class='card-body'>
          <blockquote class='blockquote mb-0'>
            $body
          </blockquote>
        </div>
      </div>";
  }

}


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include "../partials/linkAndMeta.php"; ?>    
  <title>Profile || Notifications</title>
  <link rel="stylesheet" href="../static/utility.css">

  <!-- new mini navbar -->
  <link rel="stylesheet" href="../static/miniNavBar.css">

  <script defer src="./notifications.js"></script>

</head>

<body>

  <!-- navigation Bar -->
  <?php include "../partials/navbar.php"; ?>

  <!-- Mini navBar -->
  <?php include "../partials/miniNavbar.php" ?>

  <!-- notification -->
  <?php include "../partials/notification.php" ?>


  <div class="container-sm my-5">

    <h1>Notifications</h1>

    <?php

      if(!$boolNotificationEmpty){
        echo"
          <div class='container d-flex justify-content-end'>
              <button class='btn btn-info' onClick='deleteAll()'>Clear</button>
          </div>";

      }

    ?>

    <div class="container my-5" id="allNotification">

      <?php 
     if($boolNotificationEmpty){
       echo "<h2 class='my-5 mx-2'> <span class='badge bg-secondary text-wrap'> You're all caught up. ☺️ </span> </h2> ";
      }
      else{
        echo $notificationArr; 
      }
      ?>
    </div>


  </div>

</body>

</html>