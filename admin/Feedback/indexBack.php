<?php

$boolLoggedIn = false;

include "../../partials/conn.php";
session_start();
if(isset($_SESSION) and isset($_SESSION["adminLogIn"]) and isset($_SESSION["adminUsername"])){
    $sessionUsername = $_SESSION["adminUsername"];
    $boolLoggedIn = true;
}else{
    header("location: ../index.php");
}



if($boolLoggedIn){

    if(isset($_GET) and isset($_GET["sortBy"]) ){
        $sortBy = $_GET["sortBy"];
        $fetchedFeedback = "";

        if($sortBy == "New"){
            $sql = "SELECT * FROM `contactus` ORDER BY `contactus`.`date` DESC";
        }else{
            $sql = "SELECT * FROM `contactus` ORDER BY `contactus`.`date` ASC";
        }

        $result = $conn->query($sql);
        $aff = $conn->affected_rows;
    
        if($aff > 0){
            $boolFeedBackFound = true;
        
            while($data = $result->fetch_object()){
                $name = $data->{"name"};
                $name = stripcslashes($name);
                $name = htmlspecialchars($name);
    
                $email = $data->{"email"};
                $email = stripcslashes($email);
                $email = htmlspecialchars($email);
    
                $message = $data->{"message"};
                $message = stripcslashes($message);
                $message = htmlspecialchars($message);
    
                $date = $data->{"date"};
                $newDate = date("j-F Y", strtotime($date));
                $newTime = date("l, g:i a", strtotime($date));
    
                $fetchedFeedback .= "<div class='card my-4'>
                                    <div class='card-header bg-info'>
                                        $email
                                    </div>
                                    <div class='card-body'>
                                        <h5 class='card-title'>$name</h5>
                                        <p class='card-text'>$message</p>
                                        <footer class='blockquote-footer'>$newTime || $newDate</footer>
                                    </div>
                                </div>";
            }
        }else{
            $fetchedFeedback = "<span class='badge bg-info'>Empty, No Feedbacks yet</span>";
        }
        
        echo $fetchedFeedback;
        
    }
    
}
    
    


       

?>