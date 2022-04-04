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



if($boolLoggedIn){
    if( isset($_GET) and isset($_GET["del"]) and isset($_GET["qSno"]) and isset($_GET["aSno"]) and isset($_GET["rSno"]) and isset($_GET["user"]) ){
        $qSno = $_GET["qSno"];
        $aSno = $_GET["aSno"];
        $rSno = $_GET["rSno"];
        $user = $_GET["user"];
        if($user == $sessionUsername){
            // echo "hhee";
            $delSql = "DELETE FROM replies where `q_sno` = $qSno and `a_sno` = $aSno and `r_sno` = $rSno and `username` = '$sessionUsername'";
            $delResult = $conn->query($delSql);
            $aff = $conn->affected_rows;
            if($aff == 1){
                echo "delSuccess";
            }

        }
    }

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $qSno = $_POST["qSno"];
        $aSno = $_POST["aSno"];
        $rSno = $_POST["rSno"];
        $desc = $_POST["desc"];

        $desc = $conn->real_escape_string($desc);
        $editSql  = "UPDATE replies set `reply` = '$desc' where `username` = '$sessionUsername' and `q_sno` = '$qSno' and `a_sno` = '$aSno' and `r_sno` = '$rSno' ";
        $result = $conn->query($editSql);
        if($result){
            echo "success";

        }else{
            echo "fail";
        }
        

    }
}






?>