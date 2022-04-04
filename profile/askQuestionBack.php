<?php
session_start();


include "../partials/conn.php";
$alertClass="";
$alertInnerSpan="";
$alertInnerStrong="";




$email = "";
if(isset($_SESSION) and isset($_SESSION["username"])){
    $username = $_SESSION["username"];

    $sql = "SELECT * FROM allusers WHERE `username` = '$username'";
    $result = $conn->query($sql);
    $aff = $conn->affected_rows;  
    $data = $result->fetch_object();

    $email = $data->{"email"};

}else{
    header("location: ../index.php");
}

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $qTitle = $conn -> real_escape_string($_POST['qTitle']);
    $qDesc = $conn -> real_escape_string($_POST['qDesc']);
    $qCategory = $conn -> real_escape_string($_POST['sort']);
    

    if($qTitle != "" and $qDesc != ""){

        $sql = "INSERT INTO question (`email`,`username`,`title`,`titleDesc`,`category`) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss",$email,$username,$qTitle,$qDesc,$qCategory);
        $result = $stmt->execute();

        if($result){
            $alertClass = "success";
            $alertInnerStrong = "Success";
            $alertInnerSpan = "Your Question has been posted";
            $alertMssg = "<div class='alert alert-$alertClass alert-dismissible fade show' id='success' role='alert'>
                            <strong>$alertInnerStrong</strong> $alertInnerSpan
                            <button type='button' onClick='outcomeBtn()' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>";
            echo $alertMssg;
        }
        else{
            $alertClass = "danger";
            $alertInnerStrong = "Unsuccessfull";
            $alertInnerSpan = "Question not posted try Again";
            $alertMssg = "<div class='alert alert-$alertClass alert-dismissible fade show' id='fail' role='alert'>
                            <strong>$alertInnerStrong</strong> $alertInnerSpan
                            <button type='button' onClick='outcomeBtn()' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>";
            echo $alertMssg;
        } 
    }else{
        $alertClass = "danger";
        $alertInnerStrong = "Unsuccessfull";
        $alertInnerSpan = "Field Cannot be Empty";
        $alertMssg = "<div class='alert alert-$alertClass alert-dismissible fade show' id='fail' role='alert'>
                        <strong>$alertInnerStrong</strong> $alertInnerSpan
                        <button type='button' onClick='outcomeBtn()' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
        echo $alertMssg;
    }

}

?>