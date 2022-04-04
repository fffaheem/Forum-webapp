<?php

require "./conn.php";

if(isset($_GET) and isset($_GET["check"])){
    if($_GET["check"]){

        if( !(session_start()) ){
            session_start();
        }
        
        if( isset($_SESSION) and isset($_SESSION["adminLogIn"]) and isset($_SESSION["adminUsername"]) ){
            $sessionUsername = $_SESSION["adminUsername"];
        
            $sql = "SELECT * FROM admins WHERE `username` = '$sessionUsername'";
            $res = $conn -> query($sql);
            $aff = $conn -> affected_rows;
            if($aff == 1){
                $sql = "SELECT * FROM allusers WHERE `username` = '$sessionUsername'";
                $res = $conn -> query($sql);
                $aff = $conn -> affected_rows;
                if($aff != 1){
                    session_unset();
                    session_destroy();
                    echo "notFound";
                }
                
            }
            else{
                session_unset();
                session_destroy();
                echo "notFound";
            }
        }
        
        if( isset($_SESSION) and isset($_SESSION["username"]) ){
            $sessionUsername = $_SESSION["username"];
        
            $sql = "SELECT * FROM allusers WHERE `username` = '$sessionUsername'";
            $res = $conn -> query($sql);
            $aff = $conn -> affected_rows;
            if($aff != 1){
                session_unset();
                session_destroy();
                echo "notFound";
            }
        
        }
    }
}






?>