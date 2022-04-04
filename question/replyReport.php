<?php

include "../partials/conn.php";

session_start();
$boolLoggedIn = false;
if(isset($_SESSION) and isset($_SESSION["username"])){
    $sessionUsername = $_SESSION["username"];
    $boolLoggedIn = true;
    
}else{
    $boolLoggedIn = false;
}

if($boolLoggedIn){
    if( isset($_GET) and isset($_GET["ques"]) and isset($_GET["ans"]) and isset($_GET["rep"]) and isset($_GET["user"]) and isset($_GET["category"]) ){
        $qSno = $_GET["ques"];
        $aSno = $_GET["ans"];
        $rSno = $_GET["rep"];
        $replyUsername = $_GET["user"];
        $qCategory = $_GET["category"];
        
        $sql = "INSERT into report_reply (`q_sno`,`a_sno`,`r_sno`,`r_ref_sno`,`reply_user`,`q_category`,`report_user`) values(?, ?, ?, ?, ?, ?, ?)";
        $stmt =$conn->prepare($sql);
        $stmt->bind_param("iiiisss",$qSno,$aSno,$rSno,$rSno,$replyUsername,$qCategory,$sessionUsername);
        $stmt->execute();
        $aff = $conn->affected_rows;
        if($aff == 1){

            if($replyUsername != $sessionUsername){
                $sql = "INSERT INTO notifications (`q_sno`,`a_sno`,`r_sno`,`username`,`res_username`,`type`) VALUES ('$qSno','$aSno','$rSno','$replyUsername','$sessionUsername','reportReply')";
                $res = $conn->query($sql);
            }

            echo "success";

        }else{
            echo "already reported";
        }
    }else{
        echo "error";
    }
}else{
    echo "notLoggedIn";
}

?>