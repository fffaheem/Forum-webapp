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
        $oldPass = $_POST["oldPass"];
        $oldPass = $conn->real_escape_string($oldPass);
        $newPass = $_POST["newPass"];
        // echo "$oldPass --> $newPass";
        $lengthPass = strlen($newPass);
        $newPass = $conn->real_escape_string($newPass);




        if($lengthPass > 6){
            $sql = "SELECT * FROM `allusers` WHERE `username` = '$sessionUsername';";
            $result = $conn->query($sql);
            $aff = $conn->affected_rows;
            if($aff==1){
                $data = $result->fetch_object();
                $passwordInDatabase = $data->{"password"};
                if(password_verify($oldPass,$passwordInDatabase)){
                    // echo "cool";
                    $newPasswordHash = password_hash($newPass,PASSWORD_DEFAULT);

                    $sql = "UPDATE allusers set `password` = '$newPasswordHash' where `username` = '$sessionUsername'";
                    $result = $conn->query($sql);
                    if($result){
                        $sql = "INSERT INTO notifications (`username`,`message`,`type`) VALUES ('$sessionUsername','You recently changed your Password','profileUser')";
                        $res = $conn -> query($sql);

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

        // echo $lengthPass;
    }


}




?>