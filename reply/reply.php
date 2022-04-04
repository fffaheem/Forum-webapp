<?php 

include "../partials/conn.php";

$boolRepliesExist = false;
$sql = "SELECT * FROM replies where `q_sno` = '$qSno' and `a_sno` = '$aSno'";
$result = $conn->query($sql);
$aff = $conn->affected_rows;
if($aff>0){
    $boolRepliesExist = true;
}

if($boolRepliesExist){
    $replies = "";
    $tableSno = 1;
    while($data = $result->fetch_object()){
        $rSno = $data->{"r_sno"};
        $rSno = stripcslashes($rSno);
        $rSno = htmlspecialchars($rSno);
        $rqCategory = $data->{"q_category"};
        $rqCategory = stripcslashes($rqCategory);
        $rqCategory = htmlspecialchars($rqCategory);
        $rUsername = $data->{"username"};
        $rUsername = stripcslashes($rUsername);
        $rUsername = htmlspecialchars($rUsername);
        $rEmail = $data->{"email"};
        $rEmail = stripcslashes($rEmail);
        $rEmail = htmlspecialchars($rEmail);
        $reply = $data->{"reply"};
        $reply = stripcslashes($reply);
        $reply = htmlspecialchars($reply);
        $rTime = $data->{"time"};
        $rTime = stripcslashes($rTime);
        $rTime = htmlspecialchars($rTime);
        $newDate = date("j-F Y", strtotime($rTime));
        $newTime = date("l, g:i a", strtotime($rTime));

        if($boolLoggedIn){
            $repliesAnchor = "<a href='../AllUsers/userProfile.php?user=$rUsername' class='links'>$rUsername </a>";
        }else{
            $repliesAnchor = "$rUsername";
        }

        $replies .= 
        "<div class='myTable' id='rep-$rUsername-$rSno'>
            <div class='d-flex border-bottom border-secondary text-white replies'>
                <div class='me-3'>$tableSno</div>
                <div class='me-3'> $repliesAnchor </div>
                <div class='reportRep' onClick='reportRepFunc(this,$qSno,$aSno,$rSno,`$rUsername`,`$rqCategory`)'> <i class='far fa-flag'></i> </div>
            </div>
            <div style='word-break: break-word;' class='border-bottom border-secondary fs-6 text-white reply-replies spaceRetainer'>$reply</div>
            <div class='text-secondary'>$newDate || $newTime</div>
        </div>";

        $tableSno++;


    }

    $tables = "
    <div class='replyTable' >
        $replies 
    </div>
    ";

}else{
    $tables = "
    <div class='d-flex align-items-center' style='height:100%;'>
        <h3> <span class='badge bg-dark'> Currently, no Replies </span> </h3>
    </div>";
}




?>


