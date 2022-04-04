<?php

include "../partials/conn.php";
session_start();
$boolLoggedin = false;
if(isset($_SESSION) and isset($_SESSION["username"]) ){
  $sessionUsername = $_SESSION["username"];
  $boolLoggedin = true;

}else{
  header("location: ../index.php");

}



if($boolLoggedin){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $email = $_POST["userEmail"];

        $sql = "SELECT * FROM `admins` WHERE `email` = '$email';";
        $result = $conn->query($sql);
        $aff = $conn->affected_rows;
        if($aff > 1){
            echo "yes";

        }else{
            echo "no";
        }
        
    }


}




?>