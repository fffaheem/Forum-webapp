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
    if(isset($_GET) and isset($_GET["search"])){
        $search = $_GET["search"];
        $search = $conn->real_escape_string($search);
        // echo $search;
        $sql = "SELECT * FROM view_user where `username` LIKE '%{$search}%' OR `email` LIKE '%{$search}%'
                OR `userdesc` LIKE '%{$search}%'";
        $result = $conn->query($sql);
        $allUsersCard = "";
        if($result){
            $aff = $conn->affected_rows;
            if($aff > 0){
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
                        <div class='d-flex flex-column'>
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
            }else{
                $allUsersCard .= "<h1 class='text-center wrap text-wrap my-5'> <span class='badge bg-secondary'> No Result </span> </h1>";
            }
        }else{
            $allUsersCard .= "<h1 class='text-center wrap text-wrap my-5'> <span class='badge bg-secondary'> No Result </span> </h1>";
        }

        $cancel = "<div class='container fs-3 d-flex justify-content-end' onclick='searchCancelFunc()'> <i class='fas fa-times cancelSearch'></i> </div>";
        echo $cancel . $allUsersCard;

    }else{
        echo "fail";
    }

}



?>