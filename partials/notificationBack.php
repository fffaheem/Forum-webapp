<?php
$website = "/forumWebsite";
require "../partials/conn.php";

session_start();

$boolLoggedIn = false;

if(isset($_SESSION) and isset($_SESSION["username"])){
    $boolLoggedIn = true;
    $sessionUsername = $_SESSION["username"];
}

$notificationIcon = "";

if($boolLoggedIn){
    if(isset($_GET) and isset($_GET["update"])){
        $sql = "SELECT * FROM `notifications` WHERE `username` = '$sessionUsername'";
        $res = $conn->query($sql);
        $aff = $conn->affected_rows;
        $span = "";
        if($aff > 0){
            $span = "<span class='position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger'>
                        $aff
                    </span>";
        }else{
            $span = "";
        }


        $notificationIcon = "
            <a href='$website/profile/notifications.php' class='btn btn-primary position-relative mx-3 fs-6' id='notification'>
                <i class='fas fa-bell'></i>
                $span
            </a>
        ";
    }else{
        $notificationIcon = "";
    }
}else{
    $notificationIcon = "";
}

echo $notificationIcon;

?>