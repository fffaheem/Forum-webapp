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
        $password = $_POST["password"];

        // checking if not Admin
        $sql = "SELECT * FROM admins where `username` = '$sessionUsername'";
        $result = $conn->query($sql);
        $aff = $conn->affected_rows;
        if($aff != 1){
            $sql = "SELECT * FROM `allusers` WHERE `username` = '$sessionUsername';";
            $result = $conn->query($sql);
            $aff = $conn->affected_rows;
            if($aff == 1){
                $data = $result->fetch_object();
                $passwordInDatabase = $data->{"password"};
                if(password_verify($password,$passwordInDatabase)){
                    // echo "cool";
                    $sql = "DELETE from question where `username` = '$sessionUsername' ";
                    $result = $conn->query($sql);
                    
                    $sql = "DELETE from answers where `username` = '$sessionUsername'";
                    $result = $conn->query($sql);
                    
                    $sql = "DELETE from replies where `username` = '$sessionUsername'";
                    $result = $conn->query($sql);
                    
                    $sql = "DELETE from question_like where `likedby` = '$sessionUsername'";
                    $result = $conn->query($sql);
                    
                    $sql = "DELETE from answer_like where `likedby` = '$sessionUsername'";
                    $result = $conn->query($sql);
                    
                    $sql = "DELETE from report_ans where `r_user` = '$sessionUsername'";
                    $result = $conn->query($sql);
                    
                    $sql = "DELETE from report_ques where `r_user` = '$sessionUsername'";
                    $result = $conn->query($sql);
                    
                    $sql = "DELETE from report_reply where `report_user` = '$sessionUsername'";
                    $result = $conn->query($sql);
                    
                    $sql = "DELETE from allusers where `username` = '$sessionUsername'";
                    $result = $conn->query($sql);

                    if($result){
                        session_unset();
                        session_destroy();
                        echo "success";
                    }else{
                        echo "fail";
                    }


                }
                else{
                    echo "invalidPassword";
                }

            }
        }
        
    }


}




?>