<?php
function getDOB($inputYear,$inputMonth,$inputDay){
    $month = "";
    if($inputMonth == "01"){
      $month = "Jan";
    }
    else if($inputMonth == "02"){
      $month = "Feb";
    }
    else if($inputMonth == "03"){
      $month = "March";
    }
    else if($inputMonth == "04"){
      $month = "April";
    }
    else if($inputMonth == "05"){
      $month = "May";
    }
    else if($inputMonth == "06"){
      $month = "June";
    }
    else if($inputMonth == "07"){
      $month = "July";
    }
    else if($inputMonth == "08"){
      $month = "Aug";
    }
    else if($inputMonth == "09"){
      $month = "Sep";
    }
    else if($inputMonth == "10"){
      $month = "Oct";
    }
    else if($inputMonth == "11"){
      $month = "Nov";
    }
    else if($inputMonth == "12"){
      $month = "Dec";
    }
    $dobYear = "$inputDay-$month-$inputYear";
    return $dobYear;
}
  
function calAge($inputYear,$inputMonth,$inputDay){
    $todayYear = date("Y");
    $todayMonth = date("m");
    $todayDate = date("d");
    $ageEqn = "$todayYear.$todayMonth$todayDate" - "$inputYear.$inputMonth$inputDay";
    $ageEqn = floor($ageEqn);  
    return $ageEqn;
}
  

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
    if(isset($_GET) and isset($_GET['user'])){
        $user = $_GET["user"];

        if($user == $sessionUsername){
            header("location: ../profile/profile.php");
        }

        $sql = "SELECT * FROM allusers where `username` = '$user'";
        $result = $conn->query($sql);
        $aff = $conn->affected_rows;
        if($aff == 1 ){
          $data = $result->fetch_object();
          $username = $data->{"username"};
          $username = stripcslashes($username);
          $username = htmlspecialchars($username);
          $fullname = $data->{"fullname"};
          $fullname = stripcslashes($fullname);
          $fullname = htmlspecialchars($fullname);
          $email = $data->{"email"};
          $email = stripcslashes($email);
          $email = htmlspecialchars($email);
          $dob = $data->{"dob"};
          $dob = stripcslashes($dob);
          $dob = htmlspecialchars($dob);
          $OrgDob = $data->{"dob"};
          $OrgDob = stripcslashes($OrgDob);
          $OrgDob = htmlspecialchars($OrgDob);
          $userdesc = $data->{"userdesc"};
          $userdesc = stripcslashes($userdesc);
          $userdesc = htmlspecialchars($userdesc);
          $showProfile = $data->{"show_profile"};
      
          if($showProfile == "public"){
            $type = "<span class='fs-5 badge bg-success'>Public</span>";
          }else{
            $type = "<span class='fs-5 badge bg-danger'>Private</span>";          
          }
      
          $userDP = $data->{"DP"};
          $userDPLocation = "../images/$userDP";
      
          if($fullname == NULL || $fullname == ""){
            $fullname = "NA";
          }
      
          if($dob == NULL || $dob == ""){
            $dob = "NA";
            $age = "NA";
          }else{
            $dobYear = substr($dob,0,4);
            $dobMonth = substr($dob,5,2);
            $dobDay = substr($dob,8,2);
            $dob = getDOB($dobYear,$dobMonth,$dobDay);
            $age = calAge($dobYear,$dobMonth,$dobDay);
          }
      
          if($userdesc == NULL || $userdesc == ""){
            $userdesc = "NA";
          }
          
      
      
        }else{
          echo "fail";
            header("location: ./index.php");
        }
      
        $descForm =   "
        <div class='container' id='descForm'>
          <div class='rightContainer'>
            <h5>Name: </h5>
            <span class='breakWord' id='fullnameF'>$fullname</span>
          </div>
          <div class='rightContainer'>
            <h5>Email: </h5>
            <span class='breakWord'>$email</span>
          </div>
          <div class='rightContainer'>
            <h5>Age: </h5>
            <span id='ageF'>$age</span>
          </div>
          <div class='rightContainer'>
            <h5>DOB: </h5>
            <span id='dobF' class=$OrgDob>$dob</span>
          </div>
          <div class='rightContainer'>
            <h5>Description: </h5>
            <span id='descF'>$userdesc</span>
          </div>
        </div> ";
    }else{
        header("location: ./index.php");
    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include "../partials/linkAndMeta.php"; ?>    
  <title>User</title>

  <link rel="stylesheet" href="../static/commonProfile.css">
  <link rel="stylesheet" href="../static/miniNavBar.css">

  <!-- new mini navbar -->
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

  <div id="sectionContainer" class="container-sm mt-5">

    <div id="left">
      <div class="container">
        <?php
          $img = "<img src='$userDPLocation' alt='Error Occurred' onerror='this.onerror=null;this.src=`../images/noDP.jpg`;' id='userDp'>";
          echo $img;
        ?>
        <div id='askPrivate'>
          <div class='d-flex flex-column'>
            <h5 class='my-2'>Account Type : </h5>
              <?php
                echo $type;
              ?>
          </div>
        </div>
      </div>
    </div>

    <div id="right" class='mb-4'>
      <h3 class='text-center mb-4 '>Profile Description</h3>
      <?php
        echo $descForm;
      ?>
    </div>

  </div>

</body>
</html>