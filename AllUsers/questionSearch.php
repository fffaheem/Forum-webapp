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

if(isset($_GET) and isset($_GET["search"]) and isset($_GET["user"]) ){
    $searchQuery = $_GET["search"];
    $searchQuery = $conn->real_escape_string($searchQuery);

    $username = $_GET["user"];

    $boolQuestionEmpty = true;
    $questionArr = "";
    $sql = "SELECT * FROM question WHERE `email` LIKE '%$searchQuery%' OR
                                    `title` LIKE '%$searchQuery%' OR 
                                    `titledesc` LIKE '%$searchQuery%' OR 
                                    `category` LIKE '%$searchQuery%' OR 
                                    `time` LIKE '%$searchQuery%' 
                                    HAVING `username` = '$username'
                                    ORDER BY `question`.`time` DESC";
    $result = $conn->query($sql);
    $aff = $conn->affected_rows;
    if($aff>0){

        $boolQuestionEmpty = false;
        
        while($data=$result->fetch_object()){

            $boolUserHasLikedThis = false;
            $qSno = $data->{"q_sno"};

            // fetching number of likes
            $sql1 = "SELECT * FROM question_like WHERE `q_sno` = '$qSno' ";
            $result1 = $conn->query($sql1);
            $no_ofLikes = $conn->affected_rows;

            // fetching if user has liked it or not
            $sql2 = "SELECT * FROM question_like WHERE `q_sno` = '$qSno' and `likedBy` = '$sessionUsername' ";
            $result1 = $conn->query($sql2);
            $aff2 = $conn->affected_rows;
            if($aff2==1){
                $boolUserHasLikedThis = true;
            }

            if($boolUserHasLikedThis){
                $likeClass = "fas";
            }
            else{
                $likeClass = "far";
            }

            $email = $data->{"email"};
            $qTitle = $data->{"title"};
            $qTitle = stripcslashes($qTitle);
            $qTitle = strip_tags($qTitle);
            $qDesc = $data->{"titledesc"};
            $qDesc = stripcslashes($qDesc);
            $qDesc = htmlspecialchars($qDesc);
            $qCategory = $data->{"category"};
            $qTime = $data->{"time"}; 
            $newDate = date("j-F Y", strtotime($qTime));
            $newTime = date("l, g:i a", strtotime($qTime));

            $questionArr .= "
            <div class='card my-5 bg-dark text-light'>
            <div class='card-header'>
                $username || $email
            </div>
            <div class='card-body'>
                <figcaption class='blockquote-footer'>
                Category: <cite title='Source Title'>$qCategory</cite>
                </figcaption>
                <h5 class='card-title'>$qTitle</h5>
                <p class='card-text spaceRetain'>$qDesc</p>
                <div class= 'd-flex justify-content-between align-items-center'>
                    <a href='../question/question.php?ques=$qSno&user=$username' class='btn btn-secondary'>Open Question</a>
                    <div class='d-flex flex-column align-items-center'> 
                    <i class='$likeClass fa-heart mx-3' onClick='likeFunc(this,$qSno,`$username`)' ></i>
                    <small class='my-2'>$no_ofLikes</small>
                    </div>
                </div>
            </div>
            <div class='card-footer text-muted'>
                $newDate || $newTime
            </div>
            </div>";

        }
    } 
    else{
        $questionArr = "<h1 class='text-center wrap text-wrap my-5'> <span class='badge bg-secondary'> No Result </span> </h1>";
    }

    $cancel = "<div class='container fs-3 d-flex justify-content-end' onclick='searchCancelFunc()'> <i class='fas fa-times cancelSearch'></i> </div>";
    echo $cancel . $questionArr;
}
?>