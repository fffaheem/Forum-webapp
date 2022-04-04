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
    $sql = "SELECT * FROM replies ORDER BY `replies` . `time` desc;";
    $result = $conn->query($sql);
    $aff = $conn->affected_rows;

    $repliesArr = ""; 
    if($aff > 0){
        while( $data = $result->fetch_object() ){
            $rSno = $data->{"r_sno"};
            $rSno = stripcslashes($rSno);
            $rSno = htmlspecialchars($rSno);

            $aSno = $data->{"a_sno"};
            $aSno = stripcslashes($aSno);
            $aSno = htmlspecialchars($aSno);

            $qSno = $data->{"q_sno"};
            $qSno = stripcslashes($qSno);
            $qSno = htmlspecialchars($qSno);

            $qCategory = $data->{"q_category"};
            $qCategory = stripcslashes($qCategory);
            $qCategory = htmlspecialchars($qCategory);

            $email = $data->{"email"};
            $email = stripcslashes($email);
            $email = htmlspecialchars($email);

            $rUsername = $data->{"username"};
            $rUsername = stripcslashes($rUsername);
            $rUsername = htmlspecialchars($rUsername);

            $reply = $data->{"reply"};
            $reply = stripcslashes($reply);
            $reply = htmlspecialchars($reply);

            $time = $data->{"time"};
            $newDate = date("j-F Y", strtotime($time));
            $newTime = date("l, g:i a", strtotime($time));

            // question detail
            $quesSql = "SELECT * FROM question WHERE `q_sno` = '$qSno'";
            $resultQuesSql = $conn->query($quesSql);
            $dataQues = $resultQuesSql->fetch_object();
            $quesAskUsername = $dataQues->{"username"};

            // Report Count Query 
            $sqlReport = "SELECT * FROM report_reply where `r_sno` = '$rSno'";
            $resultReport = $conn->query($sqlReport);
            $reportCount = $conn->affected_rows;
            //=================
            
            $repliesArr .= "
            <div class='card text-white bg-dark mb-3 replyCard'>
                <div class='card-header d-flex  justify-content-between'>
                    <div> $qCategory </div>
                    <div> Report Count: $reportCount </div>
                </div>
                <div class='card-body'>
                    <h5 class='card-title'>By: $rUsername</h5>
                    <p class='card-text spaceRetain'>$reply</p>
                    <div class='d-flex justify-content-between'>
                        <p class='card-text'>$newTime || $newDate</p>
                        <div>
                            <a href='../../question/question.php?ques=$qSno&user=$quesAskUsername#rep-$rUsername-$rSno' class='btn btn-success'> Open </a> 
                            <button class='btn btn-danger' onClick='repliesDeleteFunc(this,$rSno)'> Delete </button>
                        </div>
                    </div>
                </div>
            </div>";
        }
    }else{
        $repliesArr =  "<span class='badge bg-info'>Empty, No Replies Founds </span>";
    }

    $sno = 1;
    include "../../partials/categories.php"; 
    $sortingForm = "
    <div class='container my-5 flex-row'>
        <div class='row gx-5'>
            <div class='col'>
                <label for='sort'>Sort By: </label>
                <select class='form-select' aria-label='Default select example' onchange='getCategory()' name='sortBy' id='sortBy'>
                    <option selected>New</option>         
                    <option value='old'>Old</option>
                    <option value='report'>Report Count</option>
                </select>
            </div>

            <div class='col'>                
                <label for='sort'>Category: </label>
                <select class='form-select' aria-label='Default select example' onchange='getCategory()' name='category' id='category'>
                    <option selected>All</option>
                    $options
                </select>
            </div>
        </div>        
    </div>";




}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include "../../partials/linkAndMeta.php"; ?>
    <link rel="stylesheet" href="../../static/adminNavbar.css">
    <title>Admin || All Replies</title>
    <script defer src="./index.js"></script>
    <link rel="stylesheet" href="../../static/utility.css">
</head>
<body>

    <?php
        require "../../partials/adminNavbar.php";
    ?>
    
    <?php
        require "../../partials/notification.php";
    ?>

    <section>
        <div id= "sortingForm">
           <?php
                echo $sortingForm;
           ?>
        </div>
        <div class="container my-5" id="repliesContainer">
            <?php
                echo $repliesArr;
            ?>
        </div>
    </section>

</body>
</html>