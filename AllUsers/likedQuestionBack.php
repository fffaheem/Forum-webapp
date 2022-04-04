<?php
session_start();
include "../partials/conn.php";
$boolLoggedIn = false;
if(isset($_SESSION) and isset($_SESSION["username"])){
  $sessionUsername = $_SESSION["username"];
  $boolLoggedIn = true;

}else{
  header("location: ../index.php");
}

if($boolLoggedIn){
    if(isset($_GET) and isset($_GET["sortBy"]) and isset($_GET["category"]) and isset($_GET["user"]) ){
        $questionArr = "";
        $sortBy = $_GET["sortBy"];
        $category = $_GET["category"];
        $username = $_GET["user"];

        if($category == "All"){
            if($sortBy=="new"){
                $sql = 
                "SELECT question.q_sno,question_like.like_sno, question.email, question.title, question.titledesc, question.category, question.time as qTime,question_like.askedby,question_like.likedby,question_like.time as qLikeTime
                FROM question 
                inner JOIN question_like
                ON question.q_sno= question_like.q_sno
                HAVING question_like.likedby = '$username'
                ORDER BY question.time desc;";
            }
            else if($sortBy=="old"){
                $sql = 
                "SELECT question.q_sno,question_like.like_sno, question.email, question.title, question.titledesc, question.category, question.time as qTime,question_like.askedby,question_like.likedby,question_like.time as qLikeTime
                FROM question 
                inner JOIN question_like
                ON question.q_sno= question_like.q_sno
                HAVING question_like.likedby = '$username'
                ORDER BY question.time asc;";
            }
            else if($sortBy=="oldLiked"){
                $sql = 
                "SELECT question.q_sno,question_like.like_sno, question.email, question.title, question.titledesc, question.category, question.time as qTime,question_like.askedby,question_like.likedby,question_like.time as qLikeTime
                FROM question 
                inner JOIN question_like
                ON question.q_sno= question_like.q_sno
                HAVING question_like.likedby = '$username'
                ORDER BY question_like.time asc;";
            }
            else if($sortBy=="Recently Liked"){
                $sql = 
                "SELECT question.q_sno,question_like.like_sno, question.email, question.title, question.titledesc, question.category, question.time as qTime,question_like.askedby,question_like.likedby,question_like.time as qLikeTime
                FROM question 
                inner JOIN question_like
                ON question.q_sno= question_like.q_sno
                HAVING question_like.likedby = '$username'
                ORDER BY question_like.time desc;";
            }
            
        }else{
            if($sortBy=="new"){
                $sql = 
                "SELECT question.q_sno,question_like.like_sno, question.email, question.title, question.titledesc, question.category, question.time as qTime,question_like.askedby,question_like.likedby,question_like.time as qLikeTime
                FROM question 
                inner JOIN question_like
                ON question.q_sno= question_like.q_sno
                HAVING question_like.likedby = '$username' and question.category = '$category'
                ORDER BY question.time desc;";
            }
            else if($sortBy=="old"){
                $sql = 
                "SELECT question.q_sno,question_like.like_sno, question.email, question.title, question.titledesc, question.category, question.time as qTime,question_like.askedby,question_like.likedby,question_like.time as qLikeTime
                FROM question 
                inner JOIN question_like
                ON question.q_sno= question_like.q_sno
                HAVING question_like.likedby = '$username' and question.category = '$category'
                ORDER BY question.time asc;";
            }
            else if($sortBy=="oldLiked"){
                $sql = 
                "SELECT question.q_sno,question_like.like_sno, question.email, question.title, question.titledesc, question.category, question.time as qTime,question_like.askedby,question_like.likedby,question_like.time as qLikeTime
                FROM question 
                inner JOIN question_like
                ON question.q_sno= question_like.q_sno
                HAVING question_like.likedby = '$username' and question.category = '$category'
                ORDER BY question_like.time asc;";
            }
            else if($sortBy=="Recently Liked"){
                $sql = 
                "SELECT question.q_sno,question_like.like_sno, question.email, question.title, question.titledesc, question.category, question.time as qTime,question_like.askedby,question_like.likedby,question_like.time as qLikeTime
                FROM question 
                inner JOIN question_like
                ON question.q_sno= question_like.q_sno
                HAVING question_like.likedby = '$username' and question.category = '$category'
                ORDER BY question_like.time desc;";
            }

        }
        $boolLikedQuesExist = false;
        $result = $conn->query($sql);
        $aff = $conn->affected_rows;
        if($aff > 0){
           
            $boolLikedQuesExist = true;
            while($data = $result->fetch_object()){
                $boolUserHasLikedThis = false;
                
                $likeSno = $data->{"like_sno"};
                $likeSno = stripcslashes($likeSno);
                $likeSno = htmlspecialchars($likeSno);
                $qSno = $data->{"q_sno"};
                $qSno = stripcslashes($qSno);
                $qSno = htmlspecialchars($qSno);
                $askedby = $data->{"askedby"}; 
                $askedby = stripcslashes($askedby);
                $askedby = htmlspecialchars($askedby);
                $qEmail = $data->{"email"};
                $qEmail = stripcslashes($qEmail);
                $qEmail = htmlspecialchars($qEmail);
                $qlikedBy = $data->{"likedby"};
                $qlikedBy = stripcslashes($qlikedBy);
                $qlikedBy = htmlspecialchars($qlikedBy);
                $qTitle = $data->{"title"};
                $qTitle = stripcslashes($qTitle);
                $qTitle = htmlspecialchars($qTitle);
                $qTitleDesc = $data->{"titledesc"};
                $qTitleDesc = stripcslashes($qTitleDesc);
                $qTitleDesc = htmlspecialchars($qTitleDesc);
                $qCat = $data->{"category"};
                $qCat = stripcslashes($qCat);
                $qCat = htmlspecialchars($qCat);
                $qTime = $data->{"qTime"};
                $qTime = stripcslashes($qTime);
                $qTime = htmlspecialchars($qTime);
                $qLikeTime = $data->{"qLikeTime"};
                $qLikeTime = stripcslashes($qLikeTime);
                $qLikeTime = htmlspecialchars($qLikeTime);
                $newDate = date("j-F Y", strtotime($qLikeTime));
                $newTime = date("l, g:i a", strtotime($qLikeTime));

                    //getting no_ofLikes 
                    $likeSql = "SELECT * FROM question_like WHERE `q_sno` ='$qSno';" ;
                    $likeResult = $conn->query($likeSql);
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


                $questionArr .= "
                <div class='card my-5 bg-dark text-light'>
                <div class='card-header'>
                    <a href='../AllUsers/userProfile.php?user=$askedby' class='links'> $askedby || $qEmail </a>
                </div>
                <div class='card-body'>
                    <figcaption class='blockquote-footer'>
                    Category: <cite title='Source Title'>$qCat</cite>
                    </figcaption>
                    <h5 class='card-title'>$qTitle</h5>
                    <p class='card-text spaceRetainer'>$qTitleDesc</p>
                    <div class= 'd-flex justify-content-between align-items-center'>
                        <a href='../question/question.php?ques=$qSno&user=$askedby' class='btn btn-secondary'>Open Question</a>
                        <div class='d-flex flex-column align-items-center'> 
                            <i class='$likeClass fa-heart mx-3' onClick='likeFunc(this,$qSno,`$askedby`)' ></i>
                            <small class='my-2'>$no_ofLikes</small>
                        </div>
                    </div>
                </div>
                <div class='card-footer text-muted'>
                    $newDate || $newTime
                </div>
                </div>";
            }
        }else{
            $questionArr = "<h1 class='my-5'> <span class='badge bg-secondary text-wrap'>No Result </span> </h1>";
        }

        echo $questionArr;
    }


}





?>