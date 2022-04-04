<?php

include "../partials/conn.php";

session_start();
if(isset($_SESSION) and isset($_SESSION["username"])){
    header("location: ../index.php");
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $email = $_POST["email"];
    // $email = $conn->real_escape_string($email)
    $boolEmailFound = false;
    $sql = "SELECT * FROM allusers WHERE `email` = '$email'";
    $res = $conn->query($sql);
    if($res){
        $aff = $conn->affected_rows;
        if($aff == 1){
            $boolEmailFound = true;
        }
    }

    if($boolEmailFound){
        $data = $res->fetch_object();
        $username = $data->{"username"};

        $bytes = random_bytes(20);
        $token = bin2hex($bytes);
        $random = rand(1,1000000000000000);
        $time = time();
        $string = $time.$random.$username.$token;
        $token = hash('sha256',$string);

        $sql = "UPDATE allusers set `token` = '$token' where `email` = '$email'";
        $res = $conn->query($sql);

        include "../partials/mail.php";

        $website = "http://localhost/ForumWebsite";
        $subject="Forgot Password";
        $html = "<p>Hello $username, Looks Like you forgot your password and you attempt to change it </p> 
                <p> If this was indeed you then click on this link  $website/forget/forget.php?token=$token&email=$email</p>
                <p>Otherwise simply ignore the message</P>";
        $userEmail = "$email";
        $location = "../signIn/signIn.php";
        
        $mailSent = smtp_mailer($userEmail,$subject,$html);

        $sql = "INSERT INTO notifications (`username`,`message`,`type`) VALUES ('$username','You recently tried to change your password','profileUser')";
        $res = $conn->query($sql);

        if($mailSent=="mailSent"){
            echo "mailSent";
        }else{
            echo "mainSentFail";
        }        

    }else{
        echo "emailNotFound";
    }
}



?>