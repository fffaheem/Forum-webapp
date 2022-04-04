<?php
session_start();
include "../partials/conn.php";

if(isset($_SESSION) and isset($_SESSION["username"])){
  $sessionUsername = $_SESSION["username"];

}else{
  header("location: ../index.php");
}


$boolRepliesExist = false;
$fetchedReplies = "";

$sql = "SELECT * FROM replies WHERE `username` = '$sessionUsername' order by `replies`.`time` desc";
$result = $conn->query($sql);
$aff = $conn->affected_rows;
if($aff>0){
  $boolRepliesExist = true;

  while($data = $result->fetch_object() ){
    $qSno = $data->{"q_sno"};
    $qSno = stripcslashes($qSno);
    $qSno = htmlspecialchars($qSno);
    $aSno = $data->{"a_sno"};
    $aSno = stripcslashes($aSno);
    $aSno = htmlspecialchars($aSno);
    $rSno = $data->{"r_sno"};
    $rSno = stripcslashes($rSno);
    $rSno = htmlspecialchars($rSno);
    $qCat = $data->{"q_category"};
    $qCat = stripcslashes($qCat);
    $qCat = htmlspecialchars($qCat);
    $rEmail = $data->{"email"};
    $rEmail = stripcslashes($rEmail);
    $rEmail = htmlspecialchars($rEmail);
    $reply = $data->{"reply"};
    $reply = stripcslashes($reply);
    $reply = htmlspecialchars($reply);
    $rUsername = $data->{"username"};
    $rUsername = stripcslashes($rUsername);
    $rUsername = htmlspecialchars($rUsername);
    $rTime = $data->{"time"};
    $rTime = stripcslashes($rTime);
    $rTime = htmlspecialchars($rTime);
    $newRepDate = date("j-F Y", strtotime($rTime));
    $newRepTime = date("l, g:i a", strtotime($rTime));

        // getting question detail
        $quesSql = "SELECT * FROM question WHERE `q_sno` = '$qSno'";
        $resultQuesSql = $conn->query($quesSql);
        $dataQues = $resultQuesSql->fetch_object();
        $quesAskUsername = $dataQues->{"username"};
        // ---------------------------------------

    $fetchedReplies .= "
    <div class='card my-5 bg-secondary bg-gradient'>
    <div class='card-header'>
      Replied by: $rUsername || $rEmail
    </div>
      <div class='card-body'>
        <blockquote class='blockquote mb-0'>
          <p class ='spaceRetainer'>$reply</p>
          <footer class='blockquote-footer text-light'>Replied on: <cite title='Source Title'>$newRepDate || $newRepTime</cite>
          </footer>
          <div class='my-3 d-flex justify-content-between'> 
            <div> <a href='../question/question.php?ques=$qSno&user=$quesAskUsername#rep-$rUsername-$rSno' class='btn btn-dark'>Open</a> </div>
            <div> 
              <button class='btn btn-danger me-2' onClick='delFunc($qSno,$aSno,$rSno,`$sessionUsername`)'> Delete </button>
              <button class='btn btn-primary editBtn'> Edit </button>
            </div>
          </div>
        </blockquote>
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
      <select class='form-select' id='sortBy' onchange='getCategory()'  aria-label='Default select example' name='sort'>
        <option selected>New</option>
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
  <title>Profile || My Replies</title>

  <link rel="stylesheet" href="../static/commonProfile.css">
  <link rel="stylesheet" href="../static/utility.css">

  <!-- new mini navbar -->
  <link rel="stylesheet" href="../static/miniNavBar.css">
  <link rel="stylesheet" href="../static/profileMyReplies.css">


  <script defer src="./myReplies.js"></script>

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
    if($boolRepliesExist){

      echo $sortByForm; 
    }
  ?>



  <div class="container-sm my-5">

    <h1>My Replies</h1>
    <div class="container" id="allReplies">      
      <?php
      if($boolRepliesExist){
        echo $fetchedReplies;
      }
      else{
        echo "
        <div class='container my-5'>
        <h1> <span class='badge bg-dark text-wrap'> It's All Empty </span> </h1>
        </div>";  
      }
      
      ?>
    </div>

  </div>

</body>

</html>