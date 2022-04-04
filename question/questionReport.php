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
    if( isset($_GET) and isset($_GET["ques"]) and isset($_GET["user"]) and isset($_GET["category"]) ){
        $qSno = $_GET["ques"];
        $qUsername = $_GET["user"];
        $qCategory = $_GET["category"];
        
        $sql = "INSERT into report_ques (`q_sno`,`q_ref_sno`,`q_user`,`q_category`,`r_user`) values(?, ?, ?, ?, ?)";
        $stmt =$conn->prepare($sql);
        $stmt->bind_param("iisss",$qSno,$qSno,$qUsername,$qCategory,$sessionUsername);
        $stmt->execute();
        $aff = $conn->affected_rows;
        if($aff == 1){

            if($qUsername != $sessionUsername){
                $sql = "INSERT INTO notifications (`q_sno`,`username`,`res_username`,`type`) VALUES ('$qSno','$qUsername','$sessionUsername','reportQues')";
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