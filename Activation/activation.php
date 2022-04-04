<!-- http://localhost/ForumWebsite/Activation/activation.php?token=$token&email=$email -->



<?php

include "../partials/conn.php";

session_start();

if(isset($_GET) and isset($_GET["token"]) and isset($_GET["email"])){
    $token = $_GET["token"];
    $email = $_GET["email"];

    $sql = "SELECT * FROM `allusers` WHERE `email` = '$email' ;";
    $result = $conn->query($sql);
    $aff = $conn->affected_rows;

    // echo $token;
    // echo "<br>";
    // echo $email;

    if($aff == 1){
        $data = $result->fetch_object();
        $databaseEmail = $data->{"email"};
        $databaseToken = $data->{"token"};

        if($databaseEmail==$email and $token == $databaseToken){
            $sql = "UPDATE `allusers` SET `status`= 'active' WHERE `email`='$databaseEmail';";
            $result = $conn->query($sql);
            
            if($result){
                session_unset();
                $_SESSION["activatedMessage"] = "Your account has been activated, You can now log-in";
                // session_destroy();
                header("location: ../signIn/signIn.php");
            }


        }else{
            header("location: ../signIn/signIn.php");
        }
    }
    else{
        header("location: ../signIn/signIn.php");
    }


}
else{
    header("location: ../signIn/signIn.php");
}

?>