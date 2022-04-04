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

//For Adding User as Admin

if($boolLoggedIn){

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $username = $_POST["username"];

        // checking if not Admin
        $sql = "SELECT * FROM admins where `username` = '$username'";
        $result = $conn->query($sql);
        $aff = $conn->affected_rows;
        if($aff != 1){
            $innerSql = "SELECT * FROM allusers where `username` = '$username'";
            $innerRes = $conn -> query($innerSql);
            $data = $innerRes -> fetch_object();
            $passHash = $data -> {"password"};
            $email = $data -> {"email"};
            $superAdmin = "no";
            $sql = "INSERT into `admins` (`username`,`email`,`password`,`superAdmin`) Values (?,?,?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss",$username,$email,$passHash,$superAdmin);
            $stmt->execute();

            if($stmt){
                echo "pass";
                $sql = "INSERT INTO notifications (`username`,`res_username`,`type`) VALUES ('$sessionUsername','$username','makeAdmin')";
                $res = $conn -> query($sql);
                $sql = "INSERT INTO notifications (`username`,`res_username`,`type`) VALUES ('$username','$sessionUsername','assignedAsAdmin')";
                $res = $conn -> query($sql);
            }else{
                echo "fail";
            }
        }else{
            echo "fail";
        }

    }

}




?>