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
    /*
    */
    $insertSql = "INSERT INTO view_user (`u_sno`,`username`,`email`,`dob`,`DP`,`userdesc`,`score`,`show`) 
    VALUES ($serialNo,'$username', '$email', '$dob', '$dp', '$userdesc', $count, '$showProfile')" ;
    $resultq = $conn->query($insertSql); 
    $serialNo = $serialNo +1;
    /*
    // Not Working Inserting One Less Idk Why
    $insertSql = "INSERT INTO view_user(`username`,`email`,`dob`,`DP`,`userdesc`,`score`,`show`) VALUES (?, ?, ?, ?, ?, ?, ?)" ;
    $stmt = $conn->prepare($insertSql);
    $stmt->bind_param("sssssds",$username, $email, $dob,$dp,$userdesc,$count,$status);
    $resultq = $stmt->execute(); 
    */
    
    // echo "$username = $count <br>";

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
      <div class='card userCardOuter my-3'>
        <div class='userImg'>
          <img src='../images/$DP' class='card-img-top' alt='Error Occurred' onerror='this.onerror=null;this.src=`../images/noDP.jpg`;'>
        </div>
        <div class='card-body userCard'>
          <div class='d-flex flex-column align-items-center'>
            $span
            <span class='badge bg-info'>Score : $score</span>
          </div>
          <h5 class='card-title' style='word-break: break-all;'>$username</h5>
          <small class='card-title' style='word-break: break-all;'>$email</small>
          <p class='card-text'>$userdesc</p>
          <a href='./userProfile.php?user=$username' class='btn btn-primary'>Open</a>
        </div>
      </div>
      ";
  }


}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php 
      include "../partials/linkAndMeta.php"; 
    ?>

    <link rel="stylesheet" href="../static/AllUser.css">
    
    <title>All Users</title>
</head>
<body>
    <!-- navigation Bar -->
    <?php   include "../partials/navbar.php";  ?>

    <!-- notification -->
    <?php include "../partials/notification.php" ?>

    <div class="container my-4">
      <h2>All Users</h2>
    </div>

    <div class="container" id = "alluser">
        <?php

          echo $allUsersCard;

        ?>

    </div>
    
    

</body>
<script src="./index.js"></script>
</html>