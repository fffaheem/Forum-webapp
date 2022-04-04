<?php

session_start();
include "../partials/conn.php";

if(isset($_SESSION) and isset($_SESSION["username"])){
  $sessionUsername = $_SESSION["username"];

}else{
  header("location: ../index.php");
}

// $boolQuestionEmpty = true;
$questionArr = "";


if(isset($_GET) and isset($_GET["sortBy"]) and isset($_GET["category"]) and isset($_GET["user"])){
    $sortBy = $_GET["sortBy"];
    $category = $_GET["category"];
    $username = $_GET["user"];

    // $sql = '';
    if($sortBy == "New"){

        if($category=="All"){
            $sql = "SELECT * FROM question WHERE `username` = '$username' ORDER BY `question`.`time` DESC";
            
        }else{
            
            $sql = "SELECT * FROM question WHERE `username` = '$username' and `category` ='$category' ORDER BY `question`.`time` DESC";
        }
    }
    else if($sortBy == "old"){

        if($category=="All"){
            $sql = "SELECT * FROM question WHERE `username` = '$username' ORDER BY `question`.`time` ASC";
            
        }else{
            
            $sql = "SELECT * FROM question WHERE `username` = '$username' and `category` ='$category' ORDER BY `question`.`time` ASC";
        }
    }
    else if($sortBy == "mostLiked"){
        if($category=="All"){
            // $sql = "SELECT *,COUNT(ql.q_sno) FROM question q,question_like ql where q.q_sno = ql.q_sno GROUP by ql.q_sno ORDER BY count(ql.q_sno) DESC;";
            $sql = "SELECT question.q_sno, question.email, question.username, question.title, question.titledesc, question.category, question.time,count(question_like.q_sno)
                    FROM question
                    LEFT JOIN question_like
                    ON question.q_sno = question_like.q_sno
                    GROUP BY question.q_sno
                    having question.username = '$username'
                    ORDER BY count(question_like.q_sno) DESC;";
            
        }else{            
            // $sql = "SELECT *,COUNT(ql.q_sno) FROM question q,question_like ql where q.q_sno = ql.q_sno and q.category ='$category' GROUP by ql.q_sno ORDER BY count(ql.q_sno) DESC;";
            $sql = "SELECT question.q_sno, question.email, question.username, question.title, question.titledesc, question.category, question.time,count(question_like.q_sno)
            FROM question 
            LEFT JOIN question_like
            ON question.q_sno = question_like.q_sno
            GROUP BY question.q_sno
            HAVING question.category = '$category' and question.username = '$username'
            ORDER BY count(question_like.q_sno) DESC;";
        }
    }

   


    $result = $conn->query($sql);
    $aff = $conn->affected_rows;


    if($aff>0){

        
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
            $email = stripcslashes($email);
            $email = htmlspecialchars($email);
            $qTitle = $data->{"title"};
            $qTitle = stripcslashes($qTitle);
            $qTitle = htmlspecialchars($qTitle);
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
                $newDate || $newDate
            </div>
            </div>";

        }      
        
    }
    else{
        $questionArr = "<h2 class='my-5 mx-2'> <span class='badge bg-secondary text-wrap'> No result </span> </h2> ";
    }
    
    
    echo $questionArr;


    // echo $sortBy;

}

?>