<?php
session_start();
include "../partials/conn.php";

$boolLoggedIn = false;
$boolQuestionEmpty = true;

if(isset($_SESSION) and isset($_SESSION["username"])){
    $sessionUsername = $_SESSION["username"];
    $boolLoggedIn = true;

}
else{
    $boolLoggedIn = false;
}

if(isset($_GET) and isset($_GET["search"])){
    $searchQuery = $_GET["search"];
    $searchQuery = $conn->real_escape_string($searchQuery);

    $boolRepliesExist = false;
    $fetchedReplies = "";
    
    $sql = "SELECT * FROM replies WHERE `q_category` LIKE '%$searchQuery%' or  
                                    `email` LIKE '%$searchQuery%' or  
                                    `reply` LIKE '%$searchQuery%' or  
                                    `time` LIKE '%$searchQuery%'  
                                    HAVING `username` = '$sessionUsername'
                                    order by `replies`.`time` desc";
    $result = $conn->query($sql);
    $aff = $conn->affected_rows;
    if($aff>0){
      $boolRepliesExist = true;
    
      while($data = $result->fetch_object() ){
        $qSno = $data->{"q_sno"};
        $qSno = stripcslashes($qSno);
        $qSno = htmlspecialchars($qSno);
        $aSno = $data->{"a_sno"};
        $aSno = stripcslashes($aSno);
        $aSno = htmlspecialchars($aSno);
        $rSno = $data->{"r_sno"};
        $rSno = stripcslashes($rSno);
        $rSno = htmlspecialchars($rSno);
        $qCat = $data->{"q_category"};
        $qCat = stripcslashes($qCat);
        $qCat = htmlspecialchars($qCat);
        $rEmail = $data->{"email"};
        $rEmail = stripcslashes($rEmail);
        $rEmail = htmlspecialchars($rEmail);
        $reply = $data->{"reply"};
        $reply = stripcslashes($reply);
        $reply = htmlspecialchars($reply);
        $rUsername = $data->{"username"};
        $rUsername = stripcslashes($rUsername);
        $rUsername = htmlspecialchars($rUsername);
        $rTime = $data->{"time"};
        $rTime = stripcslashes($rTime);
        $rTime = htmlspecialchars($rTime);
        $newRepDate = date("j-F Y", strtotime($rTime));
        $newRepTime = date("l, g:i a", strtotime($rTime));
    
            // getting question detail
            $quesSql = "SELECT * FROM question WHERE `q_sno` = '$qSno'";
            $resultQuesSql = $conn->query($quesSql);
            $dataQues = $resultQuesSql->fetch_object();
            $quesAskUsername = $dataQues->{"username"};
            // ---------------------------------------
    
        $fetchedReplies .= "
        <div class='card my-5 bg-secondary bg-gradient'>
        <div class='card-header'>
          Replied by: $rUsername || $rEmail
        </div>
          <div class='card-body'>
            <blockquote class='blockquote mb-0'>
              <p class ='spaceRetainer'>$reply</p>
              <footer class='blockquote-footer text-light'>Replied on: <cite title='Source Title'>$newRepDate || $newRepTime</cite>
              </footer>
              <div class='my-3 d-flex justify-content-between'> 
                <div> <a href='../question/question.php?ques=$qSno&user=$quesAskUsername#rep-$rUsername-$rSno' class='btn btn-dark'>Open</a> </div>
                <div> 
                  <button class='btn btn-danger me-2' onClick='delFunc($qSno,$aSno,$rSno,`$sessionUsername`)'> Delete </button>
                  <button class='btn btn-primary editBtn'> Edit </button>
                </div>
              </div>
            </blockquote>
          </div>
        </div>";
      }
    
    }
    else{
        $fetchedReplies = "<h1 class='text-center wrap text-wrap my-5'> <span class='badge bg-secondary'> No Result </span> </h1>";
    }

    $cancel = "<div class='container fs-3 d-flex justify-content-end' onclick='searchCancelFunc()'> <i class='fas fa-times cancelSearch'></i> </div>";
    echo $cancel . $fetchedReplies;
}
?>