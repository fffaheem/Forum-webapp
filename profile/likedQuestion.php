<?php
session_start();
include "../partials/conn.php";

if(isset($_SESSION) and isset($_SESSION["username"])){
  $sessionUsername = $_SESSION["username"];

}else{
  header("location: ../index.php");
}


$boolLikedQuesExist = false;
$sql = 
"SELECT question.q_sno,question_like.like_sno, question.email, question.title, question.titledesc, question.category, question.time as qTime,question_like.askedby,question_like.likedby,question_like.time as qLikeTime
FROM question 
inner JOIN question_like
ON question.q_sno= question_like.q_sno
HAVING question_like.likedby = '$sessionUsername'
ORDER BY question_like.time desc;";
$result = $conn->query($sql);
$aff = $conn->affected_rows;
if($aff > 0){
    $questionArr = "";
    $boolLikedQuesExist = true;
    while($data = $result->fetch_object()){
      $likeSno = $data->{"like_sno"};
      $likeSno = stripcslashes($likeSno);
      $likeSno = htmlspecialchars($likeSno);
      $qSno = $data->{"q_sno"};
      $qSno = stripcslashes($qSno);
      $qSno = htmlspecialchars($qSno);
      $askedby = $data->{"askedby"}; 
      $askedby = stripcslashes($askedby);
      $askedby = htmlspecialchars($askedby);
      $qEmail = $data->{"email"};
      $qEmail = stripcslashes($qEmail);
      $qEmail = htmlspecialchars($qEmail);
      $qlikedBy = $data->{"likedby"};
      $qlikedBy = stripcslashes($qlikedBy);
      $qlikedBy = htmlspecialchars($qlikedBy);
      $qTitle = $data->{"title"};
      $qTitle = stripcslashes($qTitle);
      $qTitle = htmlspecialchars($qTitle);
      $qTitleDesc = $data->{"titledesc"};
      $qTitleDesc = stripcslashes($qTitleDesc);
      $qTitleDesc = htmlspecialchars($qTitleDesc);
      $qCat = $data->{"category"};
      $qCat = stripcslashes($qCat);
      $qCat = htmlspecialchars($qCat);
      $qTime = $data->{"qTime"};
      $qTime = stripcslashes($qTime);
      $qTime = htmlspecialchars($qTime);
      $qLikeTime = $data->{"qLikeTime"};
      $qLikeTime = stripcslashes($qLikeTime);
      $qLikeTime = htmlspecialchars($qLikeTime);
      $newDate = date("j-F Y", strtotime($qLikeTime));
      $newTime = date("l, g:i a", strtotime($qLikeTime));

        //getting no_ofLikes 
        $likeSql = "SELECT * FROM question_like WHERE `q_sno` ='$qSno';" ;
        $likeResult = $conn->query($likeSql);
        $no_ofLikes = $conn->affected_rows;

        // ofcourse user has liked it 


      $likeClass = "fas";
    //   $no_ofLikes = 200;

      $questionArr .= "
      <div class='card my-5 bg-dark text-light'>
      <div class='card-header'>
        <a href='../AllUsers/userProfile.php?user=$askedby' class='links'> $askedby || $qEmail </a>
      </div>
      <div class='card-body'>
          <figcaption class='blockquote-footer'>
          Category: <cite title='Source Title'>$qCat</cite>
          </figcaption>
          <h5 class='card-title'>$qTitle</h5>
          <p class='card-text spaceRetainer'>$qTitleDesc</p>
          <div class= 'd-flex justify-content-between align-items-center'>
              <a href='../question/question.php?ques=$qSno&user=$askedby' class='btn btn-secondary'>Open Question</a>
              <div class='d-flex flex-column align-items-center'> 
                  <i class='$likeClass fa-heart mx-3' onClick='likeFunc(this,$qSno,`$askedby`)' ></i>
                  <small class='my-2'>$no_ofLikes</small>
              </div>
          </div>
      </div>
      <div class='card-footer text-muted'>
          $newDate || $newTime
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
    <select class='form-select' id='category' onchange='getCategory()'  aria-label='Default select example' name='sort'>
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
  <title>Profile || Liked Questions</title>

  <link rel="stylesheet" href="../static/commonProfile.css">
  <link rel="stylesheet" href="../static/utility.css">

  <script defer src="./likedQuestion.js"></script>

  <!-- new mini navbar -->
  <link rel="stylesheet" href="../static/miniNavBar.css">

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
    if($boolLikedQuesExist){

      echo $sortByForm; 
    }
  ?>



  <div class="container-sm my-5">

    <h1>Liked Question</h1>

    <div class="container" id="myLikedQues">
    
      <?php
    
        if($boolLikedQuesExist){
          echo $questionArr;
        }
        else{
          echo "
          <div class='container my-5'>
            <h1> <span class='badge bg-dark text-wrap'> It's All Empty, No Liked Questions Yet </span> </h1>
          </div>";  
        }

      ?>
    </div>
  </div>

</body>

</html>