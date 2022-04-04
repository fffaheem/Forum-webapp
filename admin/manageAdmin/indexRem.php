<?php

include "../../partials/conn.php";
session_start();
$boolLoggedIn = false;
if(isset($_SESSION) and isset($_SESSION["adminLogIn"]) and isset($_SESSION["adminUsername"]) and  isset($_SESSION["username"])){
    $sessionUsername = $_SESSION["adminUsername"];
    $boolLoggedIn = true;
}else{
    header("location: ../index.php");
}



if($boolLoggedIn){

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $username = $_POST["username"];
        
        $sql = "SELECT * FROM `admins` WHERE `username` = '$username';";
        $result = $conn->query($sql);
        $data = $result -> fetch_object();
        $superAdmin = $data->{"superAdmin"};
        if($superAdmin == "no"){

            $sql = "DELETE from admins where `username` = '$username'";
            $result = $conn->query($sql);

            if($result){
                if($username == $sessionUsername){
                    $sql = "INSERT INTO notifications (`username`,`message`,`type`) VALUES ('$sessionUsername','You removed yourself from admin','profileUser')";
                    $res = $conn -> query($sql);
                }else{
                    $sql = "INSERT INTO notifications (`username`,`res_username`,`type`) VALUES ('$sessionUsername','$username','removeAdmin')";
                    $res = $conn -> query($sql);
                    $sql = "INSERT INTO notifications (`username`,`res_username`,`type`) VALUES ('$username','$sessionUsername','removeBeingAdmin')";
                    $res = $conn -> query($sql);
                }
                if($sessionUsername == $username){
                    session_unset();
                    session_destroy();
                    echo "successR";
                }else{

                    echo "success";
                }
               
            }else{
                echo "fail";
            }


        }      
      

    }

}




?>