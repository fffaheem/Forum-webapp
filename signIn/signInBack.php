<?php


include "../partials/conn.php";
$boolFormSubmit = false;

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $boolFormSubmit = true;


    $username = $_POST["username"];
    $username = $conn->real_escape_string($username);
    $password = $_POST["password"];
    $password = $conn->real_escape_string($password);



    $sql = "SELECT * FROM `allusers` WHERE `username` = '$username';";
    $result = $conn->query($sql);
    $aff = $conn->affected_rows;
    
    
    if($aff==1){
        $data = $result->fetch_object();
        $status = $data->{"status"};

        if($status == "active"){
            $passwordInDatabase = $data->{"password"};
            if(password_verify($password,$passwordInDatabase)){
                echo "success";

                session_start();
                $_SESSION["username"] = $username;
                $_SESSION["loggedIn"] = "true";

            }
            else{
                echo "invalidPassword";
            }
        }else{
            echo "invalidUsername";
        }
    }
    else{
        echo "invalidUsername";
    }
}



?>