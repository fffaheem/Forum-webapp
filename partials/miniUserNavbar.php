<?php

include "../partials/conn.php";
$url = $_SERVER['REQUEST_URI'];
$urlQuery = parse_url($url, PHP_URL_QUERY);
$boolIsPrivate = "";

if(isset($_GET) and isset($_GET["user"])){
    $user = $_GET["user"];
    $user = $conn->real_escape_string($user);
    

    $sql = "SELECT * FROM allusers where `username` = '$user'";
    $result = $conn->query($sql);
    if($result){
        $aff = $conn->affected_rows;
        if($aff == 1){
            $data = $result->fetch_object();
            $showProfile = $data->{"show_profile"};

            if($showProfile == "private"){
                $boolIsPrivate = true;
            }else{
                $boolIsPrivate = false;
            }
        }
    }
}

// echo var_dump($boolIsPrivate);
$website = "/forumWebsite";
$profileLink = "$website/AllUsers/userProfile.php"."?$urlQuery";
$profileAnswers = "$website/AllUsers/answers.php"."?$urlQuery";
$profileQuestions = "$website/AllUsers/question.php"."?$urlQuery";
$profileReplies = "$website/AllUsers/replies.php"."?$urlQuery";
$profileLikedQuestion = "$website/AllUsers/likedQuestion.php"."?$urlQuery";
$profileLikedAnswer = "$website/AllUsers/likedAnswers.php"."?$urlQuery";

$url = $_SERVER['REQUEST_URI'];
$url = strtolower($url);

$profileActive ="";
$profileQuestionActive ="";
$profileAnswerActive ="";
$profileRepliesActive ="";
$profileLikedQuestionActive ="";
$profileLikedAnswerActive ="";


if($url == strtolower($profileLink) ){
    $profileActive = "text-white profileActive";
}
else if($url == strtolower($profileAnswers) ){
    $profileAnswerActive = "text-white profileActive";
}
else if($url == strtolower($profileQuestions) ){
    $profileQuestionActive = "text-white profileActive";
}
else if($url == strtolower($profileReplies) ){
    $profileRepliesActive = "text-white profileActive";
}
else if($url == strtolower($profileLikedQuestion) ){
    $profileLikedQuestionActive = "text-white profileActive";
}
else if($url == strtolower($profileLikedAnswer) ){
    $profileLikedAnswerActive = "text-white profileActive";
}

if($boolIsPrivate){
    $body = "
    <div class='d-flex flex-wrap' id='locked'>
        <div class='nav-item myNavTagsOut me-5' style = 'width: fit-content;'> 
            <div class='active nav-link'>
                Questions
            </div>
        </div>
        <div class='nav-item myNavTagsOut me-5' style = 'width: fit-content;'> 
            <div class='active nav-link'>
                Answers
            </div>
        </div>
        <div class='nav-item myNavTagsOut me-5' style = 'width: fit-content;'> 
            <div class='active nav-link'>
                Replies
            </div>
        </div>
        <div class='nav-item myNavTagsOut me-5' style = 'width: fit-content;'> 
            <div class='active nav-link'>
                Liked Questions
            </div>
        </div>
        <div class='nav-item myNavTagsOut me-5' style = 'width: fit-content;'> 
            <div class='active nav-link'>
                Liked Answers
            </div>
        </div>
    </div>";

    $color = "danger";
}else{
    $body = "
    <li class='nav-item myNavTagsOut me-5' style = 'width: fit-content;'>
        <a class='nav-link active myNavTags $profileQuestionActive' aria-current='page' href='$profileQuestions'>Questions</a>
    </li>
    <li class='nav-item myNavTagsOut me-5' style = 'width: fit-content;'>
        <a class='nav-link active myNavTags $profileAnswerActive' aria-current='page' href='$profileAnswers'>Answers</a>
    </li>
    <li class='nav-item myNavTagsOut me-5' style = 'width: fit-content;'>
        <a class='nav-link active myNavTags $profileRepliesActive' aria-current='page' href='$profileReplies'>Replies</a>
    </li>
    <li class='nav-item myNavTagsOut me-5' style = 'width: fit-content;'>
        <a class='nav-link active myNavTags $profileLikedQuestionActive' aria-current='page' href='$profileLikedQuestion'>Liked Question</a>
    </li>
    <li class='nav-item myNavTagsOut me-5' style = 'width: fit-content;'>
        <a class='nav-link active myNavTags $profileLikedAnswerActive' aria-current='page' href='$profileLikedAnswer'>Liked Answers</a>
    </li>
    ";

    $color = "success";
}


echo "
<div id='myNavOut'>
<nav class='navbar navbar-expand-xl navbar-light bg-$color py-4' id='miniNav'>
  <div class='container-fluid'>
    <button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbarNavDropdown' aria-controls='navbarNavDropdown' aria-expanded='false' aria-label='Toggle navigation'>
      <span class='navbar-toggler-icon'></span>
    </button>
    <div class='collapse navbar-collapse justify-content-center' id='navbarNavDropdown'>
      <ul class='navbar-nav'>
        <li class='nav-item myNavTagsOut me-5' style = 'width: fit-content;'>
          <a class='nav-link active myNavTags $profileActive' aria-current='page' href='$profileLink'>$user's Profile</a>
        </li>
        $body
      </ul>
    </div>
  </div>
</nav>
</div>
";
?>