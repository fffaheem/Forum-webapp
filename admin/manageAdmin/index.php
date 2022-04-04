<?php

include "../../partials/conn.php";
$boolLoggedIn = false;
$boolSuperAdmin = false;
session_start();
if(isset($_SESSION) and isset($_SESSION["adminLogIn"]) and isset($_SESSION["adminUsername"])){
    $sessionUsername = $_SESSION["adminUsername"];

    $sql = "SELECT * FROM admins WHERE `username` = '$sessionUsername'";
    $res = $conn -> query($sql);
    $data = $res -> fetch_object();
    $superA = $data->{"superAdmin"};
    if($superA == "yes"){
        $boolSuperAdmin = true;
    }else{
        $boolSuperAdmin = false;
    }

    $boolLoggedIn = true;
}else{
    header("location: ../index.php");
}

if($boolLoggedIn){
    // echo var_dump(session_save_path());


    $sql = "SELECT * FROM `admins`";
    $res = $conn -> query($sql);
    
    $serialNo = 1;
    $count = 0;
    $delSql = "DELETE from view_user";
    $delResult = $conn->query($delSql);
    
    while($data = $res -> fetch_object()){
        $email = $data -> {"email"};
        $username = $data -> {"username"};
        
        
        // getting user Data and scores from allusers table
        $innerSql = "SELECT allusers.username,allusers.email,allusers.dob,allusers.DP,allusers.userdesc,show_profile,count(question.q_sno) 
        FROM allusers
        LEFT JOIN  question
        on allusers.username = question.username
        WHERE allusers.status = 'active'
        GROUP BY allusers.username 
        HAVING allusers.username = '$username'
        ORDER BY count(question.q_sno) DESC;";
        $innerResult = $conn->query($innerSql);

        $innerData = $innerResult->fetch_object();

        $username = $innerData->{"username"};
        $email = $innerData->{"email"};
        $dob = $innerData->{"dob"};
        $userdesc = $innerData->{"userdesc"};
        $userdesc = $conn->real_escape_string($userdesc);
        $dp = $innerData->{"DP"};
        $dp = $conn->real_escape_string($dp);
        $count = $innerData->{"count(question.q_sno)"};
        $showProfile = $innerData->{"show_profile"};
    
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

        // echo var_dump($innerData);
        $count = (float)$count;

        $insertSql = "INSERT INTO view_user (`u_sno`,`username`,`email`,`dob`,`DP`,`userdesc`,`score`,`show`) 
        VALUES ($serialNo,'$username', '$email', '$dob', '$dp', '$userdesc', $count, '$showProfile')" ;
        $resultq = $conn->query($insertSql); 
        $serialNo = $serialNo +1;

    }
    


    $sql = "SELECT * FROM view_user order by `view_user` . `score` DESC";

    $result = $conn->query($sql);
    $allUsersCard = "";
    $youCard = "";
    while($data = $result -> fetch_object()){
        $deleteBtn = "";
        $username = $data->{"username"};

        // getting is  Admin a super Admin
        $innerSql = "SELECT * FROM `admins` where `username` = '$username'";
        $innerRes = $conn -> query($innerSql);
        $innerData = $innerRes -> fetch_object();
        $superAdmin = $innerData -> {"superAdmin"};
        if($superAdmin == "yes"){
            $deleteBtn = "";
            $isSuper = "<h6 class='card-title' style='word-break: break-all;'> <span class='badge bg-success my-2'> Super Admin </span> </h6>";
        }else{
            $deleteBtn = "<btn onClick='delAdminFunc(this,`$username`)' class='btn btn-danger my-1 mx-2'>Delete Account</btn>
                          <btn onClick='removeAdminFunc(this,`$username`)' class='btn btn-warning my-1 mx-2'>Remove as admin</btn>";
            $isSuper = "";
        }


        if($boolSuperAdmin and $username != $sessionUsername){
            $deleteBtn .= "<btn onClick='AssignSuperAdminFunc(this,`$username`)' class='btn btn-dark text-white my-1 mx-2'>Assign Super Admin</btn>";
        }
        


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

        if($username == $sessionUsername){
            $youCard .= "
            <div class='card my-4'>
                <div class='row g-0 justify-content-center' >
                    <div class='col-sm-2 d-flex justify-content-center align-items-center' style='width: 10rem;' >
                        <img src='../../images/$DP' class='img-fluid rounded-start' alt='Error Occurred' onerror='this.onerror=null;this.src=`../../images/noDP.jpg`;'>
                    </div>
                    <div class='col'>
                        <div class='card-body'>
                                $isSuper
                            <h5 class='card-title' style='word-break: break-all;' >$username</h5>
                            <small class='card-title' style='word-break: break-all;'>$email</small>
                            <p class='card-text'>$userdesc</p>
                            <div class='d-flex flex-row justify-content-center align-items-center'>
                                <div class='d-flex mx-2 justify-content-center adminCard'>
                                    <a href='../../AllUsers/userProfile.php?user=$username' class='btn btn-primary my-1 mx-2'>Open Account</a>
                                    $deleteBtn
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
        }else{
            $allUsersCard .= "
            <div class='card my-4'>
                <div class='row g-0 justify-content-center' >
                    <div class='col-sm-2 d-flex justify-content-center align-items-center' style='width: 10rem;' >
                        <img src='../../images/$DP' class='img-fluid rounded-start' alt='Error Occurred' onerror='this.onerror=null;this.src=`../../images/noDP.jpg`;'>
                    </div>
                    <div class='col'>
                        <div class='card-body'>
                                $isSuper
                            <h5 class='card-title' style='word-break: break-all;' >$username</h5>
                            <small class='card-title' style='word-break: break-all;'>$email</small>
                            <p class='card-text'>$userdesc</p>
                            <div class='d-flex flex-row justify-content-center align-items-center'>
                                <div class='d-flex mx-2 justify-content-center adminCard'>
                                    <a href='../../AllUsers/userProfile.php?user=$username' class='btn btn-primary my-1 mx-2'>Open Account</a>
                                    $deleteBtn
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
          
    
    
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include "../../partials/linkAndMeta.php"; ?>
    <link rel="stylesheet" href="../../static/AllAdminUsers.css">
    <link rel="stylesheet" href="../../static/adminNavbar.css">
    <link rel="stylesheet" href="../../static/utility.css">
    <script defer src="./index.js"></script>
    <title>Admin || Manage Admins</title>
</head>
<body>

    <?php
        require "../../partials/adminNavbar.php";
    ?>

    <?php
        require "../../partials/notification.php";
    ?>

    <section>
        <div class="container my-5" id="allAdmins">
            <h3 class='my-5'> You </h3>
            <div>
                <?php
                echo $youCard;
                ?>
            </div>
            <h3 class='my-5'> All Admins </h3>
            <div id="adminScroll">
                <?php
                echo $allUsersCard;
                ?>
            </div>
        </div>
    </section>


    <section >
        <hr>
        <div class="container" id="adminForm">
            <h3 class='my-5'> Add Admins </h3>

            <div class="container my-3" id="adminSignUpForm" >

                <form class="my-5 row gx-5" id="adminSignUpFormOut">
                    <div class="mb-3 col">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                        <small id="fullNameSmall" ></small>
                    </div>
                    <div class="mb-3 col">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                        <small id="usernameSmall"></small>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email"  required>
                        <small id="emailSmall" ></small>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password"  required>
                        <small id="passwordSmall"></small>
                    </div>
                    <div class="mb-3">
                        <label for="confirmPassword" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                        <small id="confirmPassSmall"></small>
                    </div>
                    <div class="mb-3">
                        <input type="checkbox" class="form-check-input" id="showPassword">
                        <label class="form-check-label" for="showPassword">Show Password</label>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary" id="signUp">Add admin</button>
                    </div>
                </form>
            </div>
     
        </div>
    </section>

</body>
</html>