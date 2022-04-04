<?php
include "../partials/conn.php";
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $username = $_POST["username"];
    $username = $conn->real_escape_string($username);
    $password = $_POST["password"];
    $password = $conn->real_escape_string($password);

    // echo "$username --> $password";

    $sql = "SELECT * from `admins` where `username` ='$username' ";
    $result = $conn->query($sql);
    if($result){
        $aff = $conn->affected_rows;
        if($aff == 1){

            $data = $result->fetch_object();
            $passwordInDatabase = $data->{"password"};
            if(password_verify($password,$passwordInDatabase)){
                if(isset($_SESSION)){
                    session_destroy();
                }
                session_start();
                $_SESSION["adminLogIn"] = true;
                $_SESSION["adminUsername"] = $username;
                $_SESSION["username"] = $username;
                $_SESSION["loggedIn"] = "true";

                echo "success";
            }else{
                echo "fail";
            }
        }else{
            echo "fail";
        }
    }
}

?>