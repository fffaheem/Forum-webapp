<?php

include "../../partials/conn.php";
session_start();
$boolLoggedIn = false;
if(isset($_SESSION) and isset($_SESSION["adminLogIn"]) and isset($_SESSION["adminUsername"]) and  isset($_SESSION["username"])){
    $sessionUsername = $_SESSION["adminUsername"];
    $boolLoggedIn = true;
}else{
    header("location: ../index.php");
}


if($boolLoggedIn){

    if(isset($_GET) and isset($_GET["all"])){

    
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
                $span = "<span class='badge bg-success'>Public</span>";
            }else{
                $span = "<span class='badge bg-danger'>Private</span>";
            }

            $allUsersCard.="
            <div class='card mb-1'>
                <div class='d-flex cardInner'>
                    <div class='d-flex justify-content-center align-items-center'>
                        <img src='../../images/$DP' alt='Error Occurred' style='width: 6rem;' onerror='this.onerror=null;this.src=`../../images/noDP.jpg`;'>
                    </div>
                    <div class='card-body'>
                        <div class='d-flex justify-content-end spanss'>
                            $span
                            <span href='#' class='badge bg-info mx-2'>Score: $score</span>
                        </div>
                        <h5 class='card-title allElem'>$username</h5>
                        <h6 class='card-subtitle mb-2 text-muted allElem'>$email</h6>
                        <p class='card-text allElem'>$userdesc</p>
                        <button class='btn btn-warning allElem' onClick='sendOneFunc(`$username`)'> Send </button>
                    </div>
                </div>
            </div>
            ";
        }

        echo $allUsersCard;
    }

    if(isset($_GET) and isset($_GET["search"])){
        $search = $_GET["search"];
        $sql = "SELECT * FROM view_user where `username` LIKE '%{$search}%' OR `email` LIKE '%{$search}%' OR `userdesc` LIKE '%{$search}%'";
        $result = $conn->query($sql);
        $aff = $conn -> affected_rows;
        $allUsersCard = "";
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
                    $span = "<span class='badge bg-success'>Public</span>";
                }else{
                    $span = "<span class='badge bg-danger'>Private</span>";
                }
    
                $allUsersCard.="
                <div class='card mb-1'>
                    <div class='d-flex cardInner'>
                        <div class='d-flex justify-content-center align-items-center'>
                            <img src='../../images/$DP' alt='Error Occurred' style='width: 6rem;' onerror='this.onerror=null;this.src=`../../images/noDP.jpg`;'>
                        </div>
                        <div class='card-body'>
                            <div class='d-flex justify-content-end spanss'>
                                $span
                                <span href='#' class='badge bg-info mx-2'>Score: $score</span>
                            </div>
                            <h5 class='card-title allElem'>$username</h5>
                            <h6 class='card-subtitle mb-2 text-muted allElem'>$email</h6>
                            <p class='card-text allElem'>$userdesc</p>
                            <button class='btn btn-warning allElem' onClick='sendOneFunc(`$username`)'> Send </button>
                        </div>
                    </div>
                </div>
                ";
            }
        }else{
            $allUsersCard = "<h1 class='text-center wrap text-wrap my-5'> <span class='badge bg-secondary'> No Result </span> </h1>";
        }
        
        $cancel = "<div class='container fs-3 d-flex justify-content-end' onclick='searchCancelFunc()'> <i class='fas fa-times cancelSearch'></i> </div>";
        echo $cancel . $allUsersCard;
    }
}

?>
