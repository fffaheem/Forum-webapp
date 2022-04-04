<?php

include "../partials/conn.php";

session_start();
$boolLoggedIn = false;
if(isset($_SESSION) and isset($_SESSION["username"])){
    $username = $_SESSION["username"];
    $sessionUsername = $_SESSION["username"];
    $boolLoggedIn = true;

    // kya direct yaha se username aur password lena sahih hai ?
    $sql = "SELECT * FROM allusers where `username` = '$username'";
    $result = $conn->query($sql);
    $data = $result->fetch_object();
    $loggedEmail = $data->{"email"};
    
}else{
    $boolLoggedIn = false;
}


if($boolLoggedIn){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $qSno = $_POST["qid"];
        $qSno = $conn->real_escape_string($qSno);
        $aSno = $_POST["aid"];
        $aSno = $conn->real_escape_string($aSno);
        $aUser = $_POST["aUser"];
        $aUser = $conn->real_escape_string($aUser);
        $aEmail = $_POST["aEmail"];
        $aEmail = $conn->real_escape_string($aEmail);
        $qCat = $_POST["qCategory"];
        $qCat = $conn->real_escape_string($qCat);
        $reply = $_POST["reply"];
        $reply = $conn->real_escape_string($reply);

        $sql = "INSERT INTO replies (`a_sno`,`q_sno`,`q_category`,`email`,`username`,`reply`) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iissss",$aSno,$qSno,$qCat,$aEmail,$aUser,$reply);
        $result = $stmt->execute();

        if($result){

            $sql = "SELECT * FROM replies WHERE `a_sno`='$aSno' and `q_sno`='$qSno' and `q_category`='$qCat' and `email`='$aEmail' and `username`='$aUser' and `reply`='$reply'";
            $res = $conn->query($sql);
            $data = $res->fetch_object();
            $rSno = $data->{"r_sno"};

            $sql = "SELECT * FROM answers WHERE `a_sno` = '$aSno'";
            $res = $conn->query($sql);
            $data = $res->fetch_object();
            $answerUsername = $data->{"username"};

            if($answerUsername != $aUser){
                $sql = "INSERT INTO notifications (`username`,`q_sno`,`a_sno`,`r_sno`,`res_username`,`type`) VALUES('$answerUsername','$qSno','$aSno','$rSno','$aUser','answerReplied')";
                $res = $conn->query($sql);
            }

            echo "success";
        }

    }
    /*
    if(isset($_GET) and isset($_GET["qid"]) and isset($_GET["aid"]) and isset($_GET["aUser"]) and isset($_GET["aEmail"]) and isset($_GET["qCategory"]) and isset($_GET["reply"]) ){
        $qSno = $_GET["qid"];
        $qSno = $conn->real_escape_string($qSno);
        $aSno = $_GET["aid"];
        $aSno = $conn->real_escape_string($aSno);
        $aUser = $_GET["aUser"];
        $aUser = $conn->real_escape_string($aUser);
        $aEmail = $_GET["aEmail"];
        $aEmail = $conn->real_escape_string($aEmail);
        $qCat = $_GET["qCategory"];
        $qCat = $conn->real_escape_string($qCat);
        $reply = $_GET["reply"];
        $reply = $conn->real_escape_string($reply);

        // echo "$qSno $aSno $aUser $aEmail $qCat $reply";

        $sql = "INSERT INTO replies (`a_sno`,`q_sno`,`q_category`,`email`,`username`,`reply`) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iissss",$aSno,$qSno,$qCat,$aEmail,$aUser,$reply);
        $result = $stmt->execute();

        if($result){
            echo "success";
        }
    }*/
}


if($boolLoggedIn){

    if(isset($_GET) and isset($_GET["sortBy"]) and isset($_GET["category"]) ){
        $sortBy = $_GET["sortBy"];
        $category = $_GET["category"];

        $fetchedReplies = "";
        if($sortBy == "New"){
            if($category == "All"){
                $sql = "SELECT * FROM replies where `username` = '$sessionUsername' ORDER BY `replies` . `time` DESC";
            }else{
            $sql = "SELECT * FROM replies where `username` = '$sessionUsername' and `q_category` = '$category' ORDER BY `replies` . `time` DESC";
            }
        }else{
            if($category == "All"){
                $sql = "SELECT * FROM replies where `username` = '$sessionUsername' ORDER BY `replies` . `time` ASC";
            }else{
            $sql = "SELECT * FROM replies where `username` = '$sessionUsername' and `q_category` = '$category' ORDER BY `replies` . `time` ASC";
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
                        <div> <a href='../question/question.php?ques=$qSno&user=$quesAskUsername' class='btn btn-dark'>Open</a> </div>
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

        echo $fetchedReplies;


    }

}

?>