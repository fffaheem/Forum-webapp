<?php

include "../partials/conn.php";

session_start();
$boolLoggedIn = false;
if(isset($_SESSION) and isset($_SESSION["username"])){
    $username = $_SESSION["username"];
    $sessionUsername = $_SESSION["username"];
    $boolLoggedIn = true;
    $sql = "SELECT * FROM allusers where `username` = '$username'";
    $result = $conn->query($sql);
    $data = $result->fetch_object();
    $loggedEmail = $data->{"email"};
    
}else{
    $boolLoggedIn = false;
}

if($boolLoggedIn){

    if(isset($_GET) and isset($_GET["sortBy"]) and isset($_GET["category"]) and isset($_GET["user"]) ){
        $sortBy = $_GET["sortBy"];
        $category = $_GET["category"];
        $username = $_GET["user"];

        $fetchedReplies = "";
        if($sortBy == "New"){
            if($category == "All"){
                $sql = "SELECT * FROM replies where `username` = '$username' ORDER BY `replies` . `time` DESC";
            }else{
            $sql = "SELECT * FROM replies where `username` = '$username' and `q_category` = '$category' ORDER BY `replies` . `time` DESC";
            }
        }else{
            if($category == "All"){
                $sql = "SELECT * FROM replies where `username` = '$username' ORDER BY `replies` . `time` ASC";
            }else{
            $sql = "SELECT * FROM replies where `username` = '$username' and `q_category` = '$category' ORDER BY `replies` . `time` ASC";
            }
        }


        $result = $conn->query($sql);
        $aff = $conn->affected_rows;
        if($aff < 1){
            $fetchedReplies = "<h2 class='my-5 mx-2'> <span class='badge bg-secondary text-wrap'> No result </span> </h2> ";
        }else{
            while($data = $result->fetch_object()){
                $rSno = $data->{"r_sno"};
                $rSno = stripcslashes($rSno);
                $rSno = htmlspecialchars($rSno);
                $qSno = $data->{"q_sno"};
                $qSno = stripcslashes($qSno);
                $qSno = htmlspecialchars($qSno);
                $aSno = $data->{"a_sno"};
                $aSno = stripcslashes($aSno);
                $aSno = htmlspecialchars($aSno);
                $qCategory = $data->{"q_category"};
                $qCategory = stripcslashes($qCategory);
                $qCategory = htmlspecialchars($qCategory);
                $rEmail = $data->{"email"};
                $rEmail = stripcslashes($rEmail);
                $rEmail = htmlspecialchars($rEmail);
                $rUsername = $data->{"username"};
                $rUsername = stripcslashes($rUsername);
                $rUsername = htmlspecialchars($rUsername);
                $reply = $data->{"reply"};
                $reply = stripcslashes($reply);
                $reply = htmlspecialchars($reply);
                $rTime = $data->{"time"};
                $rTime = stripcslashes($rTime);
                $rTime = htmlspecialchars($rTime);
                $newRepDate = date("j-F Y", strtotime($rTime));
                $newRepTime = date("l, g:i a", strtotime($rTime));


                // echo "$username || $qCategory || $reply \n";

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
                <div class='card-body '>
                    <blockquote class='blockquote mb-0'>
                    <p class='spaceRetainer'>$reply</p>
                    <footer class='blockquote-footer text-light'>Replied on: <cite title='Source Title'>$newRepDate || $newRepTime</cite>
                    </footer>
                    <div class='my-3 d-flex justify-content-between'> 
                        <div> <a href='../question/question.php?ques=$qSno&user=$quesAskUsername#rep-$rUsername-$rSno' class='btn btn-dark'>Open</a> </div>
                    </div>
                    </blockquote>
                </div>
                </div>";
            }

        }

        echo $fetchedReplies;


    }

}


?>