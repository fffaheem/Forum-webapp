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
        //----------- image ------------------
        $imgName = $_FILES["uploadDp"]["name"];
        $imgType = $_FILES["uploadDp"]["type"];
        $tmpName = $_FILES["uploadDp"]["tmp_name"];

        $imgExplode = explode('.',$imgName);
        $imgExt = end($imgExplode);


        $validExtension = ["png","jpg","jpeg"];

        if(in_array($imgExt,$validExtension)){
            // for image
            $time = time();
            $time = hash('sha256',$time);
            $newImgName = $sessionUsername . $time . ".$imgExt";

    
            move_uploaded_file($tmpName,"../images/".$newImgName);

            //Delete old File 
            $sql = "SELECT * FROM `allusers` where `username` = '$sessionUsername'";
            $res = $conn->query($sql);
            $data = $res->fetch_object();
            $oldName = $data->{"DP"};
            if($oldName != "noDP.jpg"){
                unlink("../images/$oldName");
            }

            $sql = "UPDATE allusers set `DP` = '$newImgName' where `username` = '$sessionUsername'";
            $result = $conn->query($sql);

            if($result){
                $sql = "INSERT INTO notifications (`username`,`message`,`type`) VALUES ('$sessionUsername','You recently changed your Profile Picture','profileUser')";
                $res = $conn -> query($sql);
                
                echo "success";
            }else{
                echo "fail";
            }
        }
    }
}


?>