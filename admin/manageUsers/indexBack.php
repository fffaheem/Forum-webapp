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

// For Searching 

if($boolLoggedIn){
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