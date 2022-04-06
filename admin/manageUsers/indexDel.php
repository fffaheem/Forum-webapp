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

// For Deleting User

if($boolLoggedIn){

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $username = $_POST["username"];

        // checking if not Admin
        $sql = "SELECT * FROM admins where `username` = '$username'";
        $result = $conn->query($sql);
        $aff = $conn->affected_rows;
        if($aff != 1){
            $sql = "SELECT * FROM `allusers` WHERE `username` = '$username';";
            $result = $conn->query($sql);
            $aff = $conn->affected_rows;
            if($aff==1){
                // echo "cool";
                $sql = "DELETE from question where `username` = '$username' ";
                $result = $conn->query($sql);

                $sql = "DELETE from answers where `username` = '$username'";
                $result = $conn->query($sql);
                
                $sql = "DELETE from replies where `username` = '$username'";
                $result = $conn->query($sql);
                
                $sql = "DELETE from question_like where `likedby` = '$username'";
                $result = $conn->query($sql);
                
                $sql = "DELETE from answer_like where `likedby` = '$username'";
                $result = $conn->query($sql);
                
                $sql = "DELETE from report_ans where `r_user` = '$username'";
                $result = $conn->query($sql);
                
                $sql = "DELETE from report_ques where `r_user` = '$username'";
                $result = $conn->query($sql);
                
                $sql = "DELETE from report_reply where `report_user` = '$username'";
                $result = $conn->query($sql);
                
                $sql = "DELETE from allusers where `username` = '$username'";
                $result = $conn->query($sql);

                if($result){
                    echo "success";
                }else{
                    echo "fail";
                }


            }       
        }else{
            echo "fail";
        }

    }

}




?>