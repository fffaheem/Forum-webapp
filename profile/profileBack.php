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
        $boolNameExist = false;
        $boolDobExist = false;
        $fullname = $_POST["fullname"];
        $fullname = $conn->real_escape_string($fullname);
        $dob = $_POST["dob"];
        $dob = $conn->real_escape_string($dob);
        $desc = $_POST["desc"];
        $desc = $conn->real_escape_string($desc);

        if($fullname != "" || $fullname != NULL){
            $boolNameExist = true;
        }
        if($dob != "" || $dob != NULL){
            $boolDobExist = true;
        }
        

        if($boolDobExist and $boolNameExist){
            $sql = "UPDATE allusers SET `fullname` = '$fullname', `dob` = '$dob', `userdesc` = '$desc' where `username` = '$sessionUsername'";
            $result = $conn->query($sql);
            if($result){
                $sql = "INSERT INTO notifications (`username`,`message`,`type`) VALUES ('$sessionUsername','You recently updated your Profile Description','profileUser')";
                $res = $conn -> query($sql);
                echo "success";
            }
            else{
                echo "fail";
            }
        }
        // echo "$fullname   $dob   $desc";
    }

    if(isset($_GET) and isset($_GET["delDp"])){
        // Delete the File also
        $sql = "SELECT * FROM `allusers` where `username` = '$sessionUsername'";
        $res = $conn->query($sql);
        $data = $res->fetch_object();
        $oldName = $data->{"DP"};
        if($oldName != "noDP.jpg"){
            unlink("../images/$oldName");
        }

        if($oldName != "noDP.jpg"){
            $sql = "UPDATE allusers set `DP` = 'noDP.jpg' where `username` = '$sessionUsername' ";
            $result = $conn->query($sql);
            if($result){
    
                $sql = "INSERT INTO notifications (`username`,`message`,`type`) VALUES ('$sessionUsername','You recently deleted your Profile Picture','profileUser')";
                $res = $conn -> query($sql);
    
                echo "success";
            }else{
                echo "fail";
            }
        }else{
            "fail";
        }

        
    }

    if(isset($_GET) and isset($_GET["showProfile"])){
        $profileShow = $_GET["showProfile"];

        $sql = "UPDATE allusers set `show_profile` = '$profileShow' where `username` = '$sessionUsername'";
        $result = $conn->query($sql);
        if($result){
            $sql = "INSERT INTO notifications (`username`,`message`,`type`) VALUES ('$sessionUsername','You recently changed your Account to $profileShow','profileUser')";
            $res = $conn -> query($sql);
            echo "success";
        }else{
            echo "fail";
        }
    }

}




?>