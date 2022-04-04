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

if($boolLoggedin){
    if(isset($_GET) and isset($_GET["user"])){
        $username = $_GET["user"];

        $boolQuestionEmpty = true;
        $questionArr = "";
        $sql = "SELECT * FROM question WHERE `username` = '$username' ORDER BY `question`.`time` DESC";
        $result = $conn->query($sql);
        $aff = $conn->affected_rows;
        if($aff>0){

            $boolQuestionEmpty = false;
            
            while($data=$result->fetch_object()){

                $boolUserHasLikedThis = false;
                $qSno = $data->{"q_sno"};

                // fetching number of likes
                $sql1 = "SELECT * FROM question_like WHERE `q_sno` = '$qSno' ";
                $result1 = $conn->query($sql1);
                $no_ofLikes = $conn->affected_rows;

                // fetching if user has liked it or not
                $sql2 = "SELECT * FROM question_like WHERE `q_sno` = '$qSno' and `likedBy` = '$sessionUsername' ";
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

                $questionArr .= "
                <div class='card my-5 bg-dark text-light'>
                <div class='card-header'>
                    $username || $email
                </div>
                <div class='card-body'>
                    <figcaption class='blockquote-footer'>
                    Category: <cite title='Source Title'>$qCategory</cite>
                    </figcaption>
                    <h5 class='card-title'>$qTitle</h5>
                    <p class='card-text spaceRetain'>$qDesc</p>
                    <div class= 'd-flex justify-content-between align-items-center'>
                        <a href='../question/question.php?ques=$qSno&user=$username' class='btn btn-secondary'>Open Question</a>
                        <div class='d-flex flex-column align-items-center'> 
                        <i class='$likeClass fa-heart mx-3' onClick='likeFunc(this,$qSno,`$username`)' ></i>
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


    }
}

$sno = 1 ;
include "../partials/categories.php";
$sortingForm =  "
      <div class='container my-5 flex-row'>
        <div class='row gx-5'>
          <div class='col'>
            <label for='sort'>Sort By: </label>
            <select class='form-select' id='sortBy' onchange='getCategory(`$user`)' aria-label='Default select example' name='sortBy'>
              <option selected>New</option>         
              <option value='old'>Old</option>
              <option value='mostLiked'>Most Liked</option>
            </select>
          </div>

          <div class='col'>
            <label for='sort'>Category: </label>
            <select class='form-select' id='category' onchange='getCategory(`$user`)' aria-label='Default select example' name='category'>
              <option selected>All</option>
                $options              
            </select>
          </div>

        </div>

      </div>";



?>


<!DOCTYPE html>
<html lang="en">

<head>
  <?php include "../partials/linkAndMeta.php"; ?>    
  <title>User || Questions</title>

  <link rel="stylesheet" href="../static/commonProfile.css">
  <link rel="stylesheet" href="../static/utility.css">

  <!-- new mini navbar -->
  <link rel="stylesheet" href="../static/miniNavBar.css">
  <link rel="stylesheet" href="../static/profile.css">
  <link rel="stylesheet" href="../static/userProfile.css">

  <script defer src="./question.js"></script>

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

        if(!$boolQuestionEmpty){
            echo $sortingForm;
        }

    ?>



    <div class="container-sm my-5">
        <?php
            $username = $_GET["user"];
            echo "<h3> $username's Questions </h3>";
        ?>
        <div class="container" id="allQues">

            <?php 
                if($boolQuestionEmpty){                
                    echo "<h2 class='my-5 mx-2'> <span class='badge bg-secondary text-wrap'> ALL EMPTY </span> </h2> ";
                }
                else{
                    echo $questionArr; 
                }
            ?>
        </div>


    </div>


</body>
</html>