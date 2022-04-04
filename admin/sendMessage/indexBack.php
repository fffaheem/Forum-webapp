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
        $message = $_POST["message"];
        $username = $_POST["username"];
        $message = $conn->real_escape_string($message);
        $boolSendAll = $_POST["sendAll"];

        if($boolSendAll == "true"){
            $sql = "SELECT * from allusers";
            $res = $conn -> query($sql);
            while($data = $res->fetch_object()){
                $username = $data->{"username"};

                $innerSql = "INSERT INTO notifications (`username`,`message`,`type`) values ('$username','$message','broadcast')";
                $innerRes = $conn ->query($innerSql);
            }
            
            echo "done";
            
        }else{
            $sql = "INSERT INTO notifications (`username`,`message`,`type`) values ('$username','$message','broadcast')";
            $res = $conn ->query($sql);
            
            echo "done";
        }
    }

}




?>