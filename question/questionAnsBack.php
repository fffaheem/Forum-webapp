<?php

include "../partials/conn.php";



session_start();
if(isset($_SESSION) and isset($_SESSION["username"])){
    $username = $_SESSION["username"];
    // url = `./questionAnsBack.php?like=true&qid=${qSno}aid=${sno}&answeredBy=${answeredBy}`;
    


    if( isset($_GET) and isset($_GET["like"]) and isset($_GET["qid"]) and isset($_GET["aid"]) and isset($_GET["answeredBy"]) ) {
        $answerSno = $_GET["aid"];
        $questionSno = $_GET["qid"];
        $answeredBy = $_GET["answeredBy"];


        $boolUserHasLikedThis = false;

        $sql = "SELECT * FROM answer_like WHERE `q_sno` = '$questionSno' and `a_sno` = '$answerSno' and `likedBy` = '$username' ";
        $result1 = $conn->query($sql);
        $aff = $conn->affected_rows;

        // $questionSno = (int)$questionSno;

        if($aff == 1){
            $boolUserHasLikedThis = true;
        }

        if(!$boolUserHasLikedThis){
            $sql = "INSERT INTO answer_like (`q_sno`,`a_sno`,`answeredby`,`likedby`) Values (?,?,?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iiss",$questionSno,$answerSno,$answeredBy,$username);
            $result = $stmt->execute();

            if($answeredBy != $username){
                $sql = "SELECT * FROM `answer_like` where `a_sno` = '$answerSno' and `answeredby` = '$answeredBy' and `likedby` = '$username' ";
                $res = $conn->query($sql);
                $data = $res->fetch_object();
                $aLikeSno = $data->{"like_sno"};
                
                $sql = "INSERT INTO notifications (`username`,`q_sno`,`a_sno`,`a_like_sno`,`res_username`,`type`) VALUES ('$answeredBy','$questionSno','$answerSno','$aLikeSno','$username','answerLiked')";
                $res = $conn->query($sql);
            }

            echo "success";
        }
        else{
            $sql = "DELETE FROM answer_like WHERE `q_sno` = '$questionSno' and `a_sno` = '$answerSno' and `likedBy` = '$username' ";
            $conn->query($sql);
            echo "fail";
        }
    }

}

?>