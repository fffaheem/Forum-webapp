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
        
        $sql = "SELECT * FROM `admins` WHERE `username` = '$sessionUsername';";
        $result = $conn->query($sql);
        $data = $result -> fetch_object();
        $superAdmin = $data->{"superAdmin"};
        if($superAdmin == "yes"){

            $sql = "UPDATE admins SET `superAdmin` = 'no' WHERE  `username` = '$sessionUsername';";
            $result = $conn->query($sql);
            
            $sql = "UPDATE admins SET `superAdmin` = 'yes' WHERE  `username` = '$username';";
            $result = $conn->query($sql);

            if($result){
                $sql = "INSERT INTO notifications (`username`,`res_username`,`type`) VALUES ('$sessionUsername','$username','makeSuperAdmin')";
                $res = $conn -> query($sql);
                $sql = "INSERT INTO notifications (`username`,`res_username`,`type`) VALUES ('$username','$sessionUsername','assignAsSuperAdmin')";
                $res = $conn -> query($sql);
                echo "pass";
               
            }else{
                echo "fail";
            }


        }else{
            echo "fail";
        } 
      

    }

}




?>