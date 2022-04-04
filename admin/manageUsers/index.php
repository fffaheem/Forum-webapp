<?php

include "../../partials/conn.php";
$boolLoggedIn = false;
session_start();
if(isset($_SESSION) and isset($_SESSION["adminLogIn"]) and isset($_SESSION["adminUsername"]) and  isset($_SESSION["username"])){
    $sessionUsername = $_SESSION["adminUsername"];
    $boolLoggedIn = true;
}else{
    header("location: ../index.php");
}


if($boolLoggedIn){

    $sql = "DELETE from view_user";
    $result = $conn->query($sql);
  
    $sql = "SELECT allusers.username,allusers.email,allusers.dob,allusers.DP,allusers.userdesc,show_profile,count(question.q_sno) 
          FROM allusers
          LEFT JOIN  question
          on allusers.username = question.username
          WHERE allusers.status = 'active'
          GROUP BY allusers.username 
          ORDER BY count(question.q_sno) DESC;";
    $result = $conn->query($sql);
  
    $serialNo = 1;
    while($data = $result->fetch_object()){
      $username = $data->{"username"};

      // If user is admin dont show
      $adminSql = "SELECT * FROM `admins` WHERE `username` = '$username'";
      $adminRes = $conn->query($adminSql);
      $adminAff = $conn->affected_rows;
      if($adminAff > 0){
        continue;
      }

      $email = $data->{"email"};
      $dob = $data->{"dob"};
      $userdesc = $data->{"userdesc"};
      $userdesc = $conn->real_escape_string($userdesc);
      $dp = $data->{"DP"};
      $dp = $conn->real_escape_string($dp);
      $count = $data->{"count(question.q_sno)"};
      $showProfile = $data->{"show_profile"};
  
      // for question like count 
      $qLike = "SELECT * FROM `question_like` where `askedby` = '$username'";
      $qLikeRes = $conn->query($qLike);
      $aff = $conn->affected_rows;
      if($aff > 0){
        $count += $aff;
      }
  
      // for answers count 
  
      $ansSql = "SELECT *,count(a_sno) FROM `answers` GROUP BY `username` having `username` = '$username'";
      $ansResult = $conn->query($ansSql);
      $aff = $conn->affected_rows;
      if($aff > 0){
  
        $data = $ansResult -> fetch_object();
        $count += $data->{"count(a_sno)"};
      }
  
      // for answer like count 
      $qLike = "SELECT * FROM `answer_like` where `answeredby` = '$username'";
      $qLikeRes = $conn->query($qLike);
      $aff = $conn->affected_rows;
      if($aff > 0){
        $count += $aff;
      }
  
      // for replies count
      $repSql = "SELECT *,count(r_sno) FROM `replies` GROUP BY `username` having `username` = '$username'";
      $repResult = $conn->query($repSql);
      $aff = $conn->affected_rows;
      if($aff > 0){
  
        $data = $repResult -> fetch_object();
        $count = $count + (0.25 * $data->{"count(r_sno)"} );
      }
  
      $count = (float)$count;

      $insertSql = "INSERT INTO view_user (`u_sno`,`username`,`email`,`dob`,`DP`,`userdesc`,`score`,`show`) 
      VALUES ($serialNo,'$username', '$email', '$dob', '$dp', '$userdesc', $count, '$showProfile')" ;
      $resultq = $conn->query($insertSql); 
      $serialNo = $serialNo +1;

  
    }
  
  
  
    $sql = "SELECT * FROM view_user order by `view_user` . `score` DESC";
    $result = $conn->query($sql);
    $allUsersCard = "";
    while($data = $result -> fetch_object()){
        $username = $data->{"username"};
        $email = $data->{"email"};
        $dob = $data->{"dob"};
        $DP = $data->{"DP"};
        $DP = stripcslashes($DP);
        $userdesc = $data->{"userdesc"};
        $userdesc = stripcslashes($userdesc);
        $score = $data->{"score"};
        $show = $data->{"show"};
        if($show == "public"){
          $span = "<span class='badge bg-success my-2'>Public</span>";
        }else{
          $span = "<span class='badge bg-danger my-2'>Private</span>";
        }

        $allUsersCard .= "
          <div class='card my-4'>
              <div class='row g-0 justify-content-center' >
                  <div class='col-sm-2 d-flex justify-content-center align-items-center' style='width: 10rem;' >
                      <img src='../../images/$DP' class='img-fluid rounded-start' alt='Error Occurred' onerror='this.onerror=null;this.src=`../../images/noDP.jpg`;'>
                  </div>
                  <div class='col'>
                      <div class='card-body'>
                          <h5 class='card-title' style='word-break: break-all;' >$username</h5>
                          <small class='card-title' style='word-break: break-all;'>$email</small>
                          <p class='card-text'>$userdesc</p>
                          <div class='d-flex flex-row justify-content-center align-items-center'>
                              <div class='d-flex mx-2 justify-content-center adminCard'>
                                  <a href='../../AllUsers/userProfile.php?user=$username' class='btn btn-primary my-1 mx-2'>Open Account</a>
                                  <btn onClick='delFunc(this,`$username`)' class='btn btn-danger my-1 mx-2'>Delete Account</btn>
                                  <btn onClick='addAdminFunc(this,`$username`)' class='btn btn-warning my-1 mx-2'>Make Admin</btn>
                              </div>
                              <div class='d-flex flex-column mx-2'>
                                  $span
                                  <span class='badge bg-info'>Score : $score</span>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>";
    }
  
  
  }
  
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include "../../partials/linkAndMeta.php"; ?>
    <link rel="stylesheet" href="../../static/adminNavbar.css">
    <link rel="stylesheet" href="../../static/AllAdminUsers.css">
    <link rel="stylesheet" href="../../static/utility.css">
    <script defer src="./index.js"></script>
    <title>Admin || Manage Users</title>
</head>
<body>

    <?php
        require "../../partials/adminNavbar.php";
    ?>
    
    <?php
        require "../../partials/notification.php";
    ?>

    <div class="container my-4">
        <h2>All Users</h2>
    </div>

    <div class="container" id = "alluser">
          <?php
  
            echo $allUsersCard;
  
          ?>
  
    </div>

</body>
</html>