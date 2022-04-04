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
    if( isset($_GET) and isset($_GET["del"]) and isset($_GET["qSno"]) and isset($_GET["aSno"]) and isset($_GET["user"]) ){
        $qSno = $_GET["qSno"];
        $aSno = $_GET["aSno"];
        $user = $_GET["user"];
        if($user == $sessionUsername){
            $delSql = "DELETE FROM answers where `q_sno` = $qSno and `a_sno`= '$aSno' and `username` = '$sessionUsername'";
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
        $desc = $_POST["desc"];
        // $desc = $conn->real_escape_string($desc);

        $checkSql = "SELECT * FROM replies where `q_sno` = '$qSno' and `a_sno` = '$aSno'";
        $resCheck = $conn->query($checkSql);
        $aff = $conn->affected_rows;
        if($aff >= 1){

            echo "editFail";

        }else{

            $desc = $conn->real_escape_string($desc);
            $editSql  = "UPDATE answers set `answer` = '$desc' where `username` = '$sessionUsername' and `q_sno` = '$qSno' and `a_sno` = '$aSno' ";
            $result = $conn->query($editSql);
            if($result){
                echo "success";

            }else{
                echo "fail";
            }
        }

    }
}






?>