<?php

include "../partials/conn.php";

session_start();
if(isset($_SESSION) and isset($_SESSION["username"])){
    header("location: ../index.php");
}


if($_SERVER["REQUEST_METHOD"] == "POST"){
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];
    $email = $_POST["email"];

    
    if($password == $confirmPassword){

        if(strlen($password) > 6) {
            $password = password_hash($password,PASSWORD_DEFAULT);


            $sql = "UPDATE allusers set `password` = '$password' where `email` = '$email'";
            $res = $conn->query($sql);
            if($res){
                $sql = "UPDATE allusers set `token` = '0' where `email` = '$email'";
                $res = $conn->query($sql);

                $sql = "SELECT * FROM `allusers` where `email` = '$email'";
                $res = $conn->query($sql);
                $data = $res->fetch_object();
                $username = $data->{"username"};
                $sql = "INSERT INTO notifications (`username`,`message`,`type`) VALUES ('$username','Password changed successfully','profileUser')";
                $res = $conn->query($sql);

                echo "passwordChanged";
                
            }else{
                echo "passwordChangeFail";
            }
            
        }else{
            echo "passwordLess";
        }
        
    }else{
        echo "confirmFail";
    }

}

?>