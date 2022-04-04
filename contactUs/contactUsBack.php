<?php
include "../partials/conn.php";
$alertClass="";
$alertInnerSpan="";
$alertInnerStrong="";

$alertMssg = "<div class='alert alert-$alertClass alert-dismissible fade show' role='alert'>
                    <strong>$alertInnerStrong</strong> $alertInnerSpan
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";

if($_SERVER["REQUEST_METHOD"]=="POST"){

    $name = $_POST["name"];
    $name = $conn->real_escape_string($name);
    $email = $_POST["email"];
    $email = $conn->real_escape_string($email);
    $message = $_POST["message"];
    $message = $conn->real_escape_string($message);

    if($name != "" and $email != "" and $message !=""){    

        $sql = "INSERT INTO contactus (`name`,`email`,`message`) VALUES(?, ?, ?);";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss",$name,$email,$message);
        $result = $stmt->execute();

        if($result){
            $alertInnerStrong = "Success";
            $alertInnerSpan = "The Message has been sent";
            $alertClass = "success";
            $alertMssg = "<div class='alert alert-$alertClass alert-dismissible fade show' role='alert'>
                            <strong>$alertInnerStrong</strong> $alertInnerSpan
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>";
            echo $alertMssg;
            
        }
        else{
            $alertInnerStrong = "Unsuccessfull";
            $alertInnerSpan = "The Message was unable to sent";
            $alertClass = "danger";
            $alertMssg = "<div class='alert alert-$alertClass alert-dismissible fade show' role='alert'>
                            <strong>$alertInnerStrong</strong> $alertInnerSpan
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>";
            
            echo $alertMssg;
        }
    }else{
        $alertInnerStrong = "Unsuccessfull";
        $alertInnerSpan = "Fields Cannot be Empty";
        $alertClass = "danger";
        $alertMssg = "<div class='alert alert-$alertClass alert-dismissible fade show' role='alert'>
                        <strong>$alertInnerStrong</strong> $alertInnerSpan
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
        
        echo $alertMssg;
    }
}

?>