<?php


session_start();
include "../partials/conn.php";
$boolLoggedIn = false;
if(isset($_SESSION) and isset($_SESSION["username"])){
  $sessionUsername = $_SESSION["username"];
  $boolLoggedIn = true;

}else{
  header("location: ../index.php");
}

if(isset($_GET) and isset($_GET["deleteAllNotification"])){
    if($_GET["deleteAllNotification"] == "true" ){

        $sql = "DELETE FROM notifications where `username` = '$sessionUsername'";
        $res = $conn->query($sql);
        if($res){
            echo "done";
        }else{
            echo "notDone";
        }


    }
}

if(isset($_GET) and isset($_GET["deleteSpecific"])){

    $sno = $_GET["deleteSpecific"];
    $sql = "DELETE FROM notifications where `username` = '$sessionUsername' and `s_no` = '$sno'";
    $res = $conn->query($sql);
    if($res){
        $sql = "SELECT * FROM notifications where `username` = '$sessionUsername'";
        $res = $conn->query($sql);
        $aff = $conn->affected_rows;
        if($aff > 0){
            echo "done";
        }else{
            echo "doneClear";
        }
        
    }else{
        echo "notDone";
    }


    
}

?>