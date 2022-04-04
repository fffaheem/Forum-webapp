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
    if( isset($_GET) and isset($_GET["del"]) and isset($_GET["qSno"]) and isset($_GET["user"]) ){
        $qSno = $_GET["qSno"];
        $user = $_GET["user"];
        if($user == $sessionUsername){
            $delSql = "DELETE FROM question where `q_sno` = $qSno and `username` = '$sessionUsername'";
            $delResult = $conn->query($delSql);
            $aff = $conn->affected_rows;
            if($aff == 1){
                echo "delSuccess";
            }

        }
    }

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $Sno = $_POST["Sno"];
        $title = $_POST["title"];
        $title = $conn->real_escape_string($title);
        $desc = $_POST["desc"];
        // $desc = $conn->real_escape_string($desc);

        $checkSql = "SELECT * FROM answers where `q_sno` = $Sno";
        $resCheck = $conn->query($checkSql);
        $aff = $conn->affected_rows;
        if($aff >= 1){
            /*
            $oldSql = "SELECT * FROM question where `q_sno` = '$Sno' and `username` = '$sessionUsername'";
            $result = $conn->query($oldSql);
            $data = $result->fetch_object();
            $oldDesc = $data->{"titledesc"};
            $oldDesc = stripcslashes($oldDesc);

            $newDesc = $oldDesc ."\n\n=================================================================\n"
                                ."=================================Edited============================\n\n". $desc;
            $newDesc = $conn->real_escape_string($newDesc); 
            $editSql  = "UPDATE question set `title` = '$title' , `titledesc` = '$newDesc' where `username` = '$sessionUsername' and `q_sno` = '$Sno'";
            $result = $conn->query($editSql);
            if($result){
                echo "success";
                
            }else{
                echo "fail";
            }
            */

            echo "editFail";

        }else{

            $desc = $conn->real_escape_string($desc);
            $editSql  = "UPDATE question set `title` = '$title' , `titledesc` = '$desc' where `username` = '$sessionUsername' and `q_sno` = '$Sno'";
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