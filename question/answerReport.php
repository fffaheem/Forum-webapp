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
    if( isset($_GET) and isset($_GET["ques"]) and isset($_GET["ans"]) and isset($_GET["user"]) and isset($_GET["category"]) ){
        $qSno = $_GET["ques"];
        $aSno = $_GET["ans"];
        $aUsername = $_GET["user"];
        $qCategory = $_GET["category"];
        
        $sql = "INSERT into report_ans (`q_sno`,`a_sno`,`a_ref_sno`,`a_user`,`q_category`,`r_user`) values(?, ?, ?, ?, ?, ?)";
        $stmt =$conn->prepare($sql);
        $stmt->bind_param("iiisss",$qSno,$aSno,$aSno,$aUsername,$qCategory,$sessionUsername);
        $stmt->execute();
        $aff = $conn->affected_rows;
        if($aff == 1){

            if($aUsername != $sessionUsername){
                $sql = "INSERT INTO notifications (`q_sno`,`a_sno`,`username`,`res_username`,`type`) VALUES ('$qSno','$aSno','$aUsername','$sessionUsername','reportAns')";
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