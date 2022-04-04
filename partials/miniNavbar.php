<?php

$website = "/forumWebsite";
$profileLink = "$website/profile/profile.php";
$profileAskQuestion = "$website/profile/askQuestion.php";
$profileMyAnswers = "$website/profile/myAnswers.php";
$profileMyQuestions = "$website/profile/myQuestions.php";
$profileMyReplies = "$website/profile/myReplies.php";
$profileLikedQuestion = "$website/profile/likedQuestion.php";
$profileLikedAnswer = "$website/profile/likedAnswer.php";

$url = $_SERVER['REQUEST_URI'];
$newUrl = explode("?",$url);
$url = $newUrl[0];
$url = strtolower($url);

$profileActive ="";
$profileAskQuestionActive ="";
$profileMyQuestionActive ="";
$profileMyAnswerActive ="";
$profileMyRepliesActive ="";
$profileLikedQuestionActive ="";
$profileLikedAnswerActive ="";


if($url == strtolower($profileLink) ){
    $profileActive = "text-white profileActive";
}
else if($url == strtolower($profileAskQuestion) ){
    $profileAskQuestionActive = "text-white profileActive";
}
else if($url == strtolower($profileMyAnswers) ){
    $profileMyAnswerActive = "text-white profileActive";
}
else if($url == strtolower($profileMyQuestions) ){
    $profileMyQuestionActive = "text-white profileActive";
}
else if($url == strtolower($profileMyReplies) ){
    $profileMyRepliesActive = "text-white profileActive";
}
else if($url == strtolower($profileLikedQuestion) ){
    $profileLikedQuestionActive = "text-white profileActive";
}
else if($url == strtolower($profileLikedAnswer) ){
    $profileLikedAnswerActive = "text-white profileActive";
}


echo "
<div id='myNavOut'>
<nav class='navbar navbar-expand-xl navbar-light bg-secondary py-4' id='miniNav'>
  <div class='container-fluid'>
    <button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbarNavDropdown' aria-controls='navbarNavDropdown' aria-expanded='false' aria-label='Toggle navigation'>
      <span class='navbar-toggler-icon'></span>
    </button>
    <div class='collapse navbar-collapse justify-content-center' id='navbarNavDropdown'>
      <ul class='navbar-nav'>
        <li class='nav-item myNavTagsOut me-5' style = 'width: fit-content;'>
          <a class='nav-link active myNavTags $profileActive' aria-current='page' href='$profileLink'>Profile</a>
        </li>
        <li class='nav-item myNavTagsOut me-5' style = 'width: fit-content;'>
          <a class='nav-link active myNavTags $profileAskQuestionActive' aria-current='page' href='$profileAskQuestion'>Ask Questions</a>
        </li>
        <li class='nav-item myNavTagsOut me-5' style = 'width: fit-content;'>
          <a class='nav-link active myNavTags $profileMyQuestionActive' aria-current='page' href='$profileMyQuestions'>My Questions</a>
        </li>
        <li class='nav-item myNavTagsOut me-5' style = 'width: fit-content;'>
          <a class='nav-link active myNavTags $profileMyAnswerActive' aria-current='page' href='$profileMyAnswers'>My Answers</a>
        </li>
        <li class='nav-item myNavTagsOut me-5' style = 'width: fit-content;'>
          <a class='nav-link active myNavTags $profileMyRepliesActive' aria-current='page' href='$profileMyReplies'>My Replies</a>
        </li>
        <li class='nav-item myNavTagsOut me-5' style = 'width: fit-content;'>
          <a class='nav-link active myNavTags $profileLikedQuestionActive' aria-current='page' href='$profileLikedQuestion'>Liked Question</a>
        </li>
        <li class='nav-item myNavTagsOut me-5' style = 'width: fit-content;'>
          <a class='nav-link active myNavTags $profileLikedAnswerActive' aria-current='page' href='$profileLikedAnswer'>Liked Answers</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
</div>
";
?>