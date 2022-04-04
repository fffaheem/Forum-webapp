<?php

include "../partials/conn.php";


if(isset($_GET) and isset($_GET["username"])){
    $username = $_GET['username'];   
    $sql = "SELECT * FROM `allusers` WHERE `username` = '$username' ";
    $result = $conn->query($sql);
    $aff = $conn->affected_rows;
    if($aff>0){
        echo "usernameFound";
    }else{
        echo "usernameNotFound";
    }
}

if(isset($_GET) and isset($_GET["email"])){
    $email = $_GET['email'];
    
    $sql = "SELECT * FROM `allusers` WHERE `email` = '$email' ";
    $result = $conn->query($sql);
    $aff = $conn->affected_rows;
    if($aff>0){
        echo "emailFound";
    }else{
        echo "emailNotFound";
    }
}



if($_SERVER["REQUEST_METHOD"]=="POST"){
    $fullname = $_POST["name"];
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];

    
    $boolFirstNameValid = false;
    $boolUsernameValid = false;
    $boolPasswordValid = false;
    $boolEmailValid = false;

    // Full Name Validation
    $regexFullName = "/[A-Z]\w+/";
    if(preg_match($regexFullName,$fullname)){
        $boolFirstNameValid = true;
    }

    // Username Validation
    $usernameLength = strlen($username)-1;
    $usernameFirstWord = $username[0];
    $usernamelastWord = $username[$usernameLength];
    $usernameMiddle = substr($username,1,$usernameLength-1);
    $regexStart = "/[a-zA-Z_]/";
    $regexMiddle = "/^[a-zA-Z0-9_.]+$/";
    $regexlast = "/^[a-zA-Z0-9_]$/";

    if(preg_match($regexStart,$usernameFirstWord)){
        if(preg_match($regexMiddle,$usernameMiddle)){
            $boolConsecutiveStop = false;
            for ($i = 1; $i < $usernameLength+1; $i++) {
                if($username[$i]=="." and $username[$i-1]=="."){
                    $boolConsecutiveStop = true;
                }                
            }
            if(!$boolConsecutiveStop and preg_match($regexlast,$usernamelastWord)){
                $sql = "SELECT * FROM `allusers` where `username` = '$username'";
                $result = $conn->query($sql);
                $aff = $conn->affected_rows;
                if($aff != 1){
                    $boolUsernameValid = true;
                }
            }
        }
    }

    



    // Email Validation
    $sql = "SELECT * FROM `allusers` WHERE `email` = '$email' ";
    $result = $conn->query($sql);
    $aff = $conn->affected_rows;
    if( filter_var($email, FILTER_VALIDATE_EMAIL) and $aff < 1){
        $boolEmailValid = true;
    }
    
    // Password Validation
    $passwordLen = strlen($password);
    if($passwordLen > 6 and ($password == $confirmPassword) ){
        $boolPasswordValid = true;
    }
    $password = $conn->real_escape_string($password);
    $confirmPassword = $conn->real_escape_string($confirmPassword);

    



    // echo var_dump($boolUsernameValid);
    // echo "<br>";
    // echo var_dump($boolPasswordValid);
    // echo "<br>";
    // echo var_dump($boolEmailValid);
    // echo "<br>";
    // echo var_dump($boolUsernameValid);

    // $check = false;
    if($boolFirstNameValid and $boolPasswordValid and $boolEmailValid and $boolUsernameValid ){     

        $passwordHash = password_hash($password,PASSWORD_DEFAULT);

        // Creating unique token 
        $bytes = random_bytes(20);
        $token = bin2hex($bytes);
        $random = rand(1,1000000000000000);
        $time = time();
        $string = $time.$random.$username.$token;
        $token = hash('sha256',$string);

        $status = "inactive";
        $DP = "noDP.jpg";
        $showProfile = "public";

        $sql = "INSERT INTO allusers (`email`,`username`,`password`,`token`,`status`,`fullname`,`DP`,`show_profile`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
      
        $stmt->bind_param("ssssssss", $email, $username, $passwordHash,$token,$status,$fullname,$DP,$showProfile);
        $stmt->execute();

        if($stmt){
            session_start();
            session_unset();
            $_SESSION['message'] = "Goto your Email Account '$email' to Activate your Account";
            // echo "success";

            include "../partials/mail.php";
            $website = "http://localhost/ForumWebsite";
            $subject="Account Activation";
            $html = "<p>Hello $fullname, Activate your account by clicking on this link $website/Activation/activation.php?token=$token&email=$email</p>
                    <p>If this wasn't you, Ignore the message</P>";
            $userEmail = "$email";
            $location = "../signIn/signIn.php";
            
            $mailSent = smtp_mailer($userEmail,$subject,$html);
            if($mailSent=="mailSent"){
                echo "mailSent";
            }else{
                // Delete so that they can make account again without telling them invalid username
                $sql = "DELETE FROM allusers WHERE `username` = '$username'";
                $res = $conn->query($sql);
                session_unset();
                session_destroy();
                echo "mailSentFail";
            }

        }else{
            echo "failure $stmt->error";
        }


    } 
}

?>