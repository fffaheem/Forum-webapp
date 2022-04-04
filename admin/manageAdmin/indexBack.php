<?php

include "../../partials/conn.php";
$boolLoggedIn = false;
session_start();
if(isset($_SESSION) and isset($_SESSION["adminLogIn"]) and isset($_SESSION["adminUsername"])){
    $sessionUsername = $_SESSION["adminUsername"];
    $boolLoggedIn = true;
}else{
    header("location: ../index.php");
}

if($boolLoggedIn){
    if(isset($_GET) and isset($_GET["email"])){
        $email = $_GET["email"];
        $sql = "SELECT * FROM allusers
                LEFT JOIN admins
                on allusers.username = admins.username
                WHERE allusers.email = '$email' or admins.email= '$email';";
        $res = $conn -> query($sql);
        $aff = $conn-> affected_rows;
        if($aff>=1){
            echo "emailFound";
        }else{
            echo "emailNotFound";
        }
    }

    if(isset($_GET) and isset($_GET["username"])){
        $username = $_GET["username"];
        $sql = "SELECT * FROM allusers
                LEFT JOIN admins
                on allusers.username = admins.username
                WHERE allusers.username = '$username' or admins.username= '$username';";
        $res = $conn -> query($sql);
        $aff = $conn-> affected_rows;
        if($aff>=1){
            echo "usernameFound";
        }else{
            echo "usernameNotFound";
        }
    }

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $username = $_POST["username"];
        $username = $conn->real_escape_string($username);
        $email = $_POST["email"];
        $email = $conn->real_escape_string($email);
        $fullname = $_POST["name"];
        $fullname = $conn->real_escape_string($fullname);
        $password = $_POST["password"];
        $password = $conn->real_escape_string($password);
        $confirmPassword = $_POST["confirmPassword"];
        $confirmPassword = $conn->real_escape_string($confirmPassword);

        $boolFullnameValid = false;
        $boolUsernameValid = false;
        $boolPasswordValid = false;
        $boolEmailValid = false;

        //Validating First Name
        $regexFullName = "/[A-Z]\w+/";
        if(preg_match($regexFullName,$fullname)){
            $boolFullnameValid = true;
        }

        // Email Validation
        $sql = "SELECT * FROM allusers
                LEFT JOIN admins
                on allusers.username = admins.username
                WHERE allusers.email = '$email' or admins.email= '$email';";
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
                    $sql = "SELECT * FROM allusers
                            LEFT JOIN admins
                            on allusers.username = admins.username
                            WHERE allusers.username = '$username' or admins.username= '$username';";
                    $result = $conn->query($sql);
                    $aff = $conn->affected_rows;
                    if($aff != 1){
                        $boolUsernameValid = true;
                    }
                }
            }
        }

        if($boolFullnameValid and $boolPasswordValid and $boolEmailValid and $boolUsernameValid ){     

            $passwordHash = password_hash($password,PASSWORD_DEFAULT);
            
            $token = "0";
            $status = "active";
            $DP = "noDP.jpg";
            $showProfile = "public";
            $superAdmin = "no";
            
            $sql = "INSERT INTO allusers (`email`,`username`,`password`,`token`,`status`,`fullname`,`DP`,`show_profile`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssss", $email, $username, $passwordHash,$token,$status,$fullname,$DP,$showProfile);
            $stmt->execute();

            if($stmt){

                
                $sql = "INSERT INTO admins (`email`,`username`,`password`,`superAdmin`) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssss", $email, $username, $passwordHash,$superAdmin);
                $stmt->execute();
                
                if($stmt){
                    echo "success";
                }else{
                    echo "fail";
                }

            }else{
                echo "fail";
            }
       

        }
  
    }
}

?>