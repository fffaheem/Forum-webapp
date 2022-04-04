<?php

$boolLoggedIn = false;

$homeActive = "";
$signInActive = "";
$aboutUsActive = "";
$contactUsActive = "";
$profileActive = "";
$allUserActive = "";

if (!isset($_SESSION)) {
    session_start();    
}

if(isset($_SESSION) and isset($_SESSION["username"])){
    $boolLoggedIn = true;
    $username = $_SESSION["username"];
}

$url = $_SERVER['REQUEST_URI'];
$newUrl = explode("?",$url);
$url = $newUrl[0];
$url = strtolower($url);

$website = "/forumWebsite";

$partials = "$website/partials";
$imagePath = "$website/images";

$homeLink = "$website/";
$profileLink = "$website/profile/profile.php";
$profileAskQuestion = "$website/profile/askQuestion.php";
$profileMyAnswers = "$website/profile/myAnswers.php";
$profileMyQuestions = "$website/profile/myQuestions.php";
$profileMyReplies = "$website/profile/myReplies.php";
$profileLikedQuestion = "$website/profile/likedQuestion.php";
$profileLikedAnswer = "$website/profile/likedAnswer.php";
$profileNotification = "$website/profile/notifications.php";
$signUp = "$website/signUp/signUp.php";
$signIn = "$website/signIn/signIn.php";
$contactUs = "$website/contactUs/contactUs.php";
$aboutUs = "$website/aboutUs/aboutUs.php";
$logOut = "$website/logOut/logOut.php";

$questionPage = "$website/question/question.php";

$allUser = "$website/AllUsers/";
$allUserProfile = "$website/AllUsers/userProfile.php";
$allUserQuestion = "$website/AllUsers/question.php";
$allUserAnswer = "$website/AllUsers/answers.php";
$allUserReplies = "$website/AllUsers/replies.php";
$allUserLikedQues = "$website/AllUsers/likedQuestion.php";
$allUserLikedAns = "$website/AllUsers/likedAnswers.php";

$admin = "$website/admin/";

$navSearch = "<form class='d-flex' id='search'>
                <input class='form-control me-2' type='search' placeholder='Search' aria-label='Search'>
                <button class='btn btn-outline-success' type='submit'>Search</button>
            </form>";
            
if($url == strtolower($signIn) || $url == strtolower($signUp) || $url == strtolower($contactUs) || $url == strtolower($aboutUs) || $url == strtolower($profileLink) || $url == strtolower($profileAskQuestion) || $url == strtolower($allUserProfile) || $url == strtolower($questionPage) || $url == strtolower($profileNotification) ){
    $navSearch = "";
}
if($url == strtolower($homeLink) || $url == strtolower($homeLink)."index.php"){
    $homeActive = "active";
}
if($url == strtolower($profileLink) || $url == strtolower($profileAskQuestion) || $url == strtolower($profileMyAnswers) || $url == strtolower($profileMyQuestions) || $url == strtolower($profileMyReplies) || $url == strtolower($profileLikedQuestion) || $url == strtolower($profileLikedAnswer)){
    $profileActive = "active";
}
if($url == strtolower($signIn) ){
    $signInActive = "active";
}
if($url == strtolower($contactUs) ){
    $contactUsActive = "active";
}
if($url == strtolower($aboutUs) ){
    $aboutUsActive = "active";
}
if($url == strtolower($allUser) || $url == strtolower($allUser) . "index.php" || $url == strtolower($allUserProfile) || $url == strtolower($allUserQuestion) || $url == strtolower($allUserAnswer) || $url == strtolower($allUserReplies) || $url == strtolower($allUserLikedQues) || $url == strtolower($allUserLikedAns) ){
    $allUserActive = "active";
}



$navHead ="";

if($boolLoggedIn){
    $profileImg = "";
    $img = "";
    $ssql = "SELECT * FROM `allusers` where `username` = '$username'";
    $result = $conn->query($ssql);
    if($result){
        $aff = $conn->affected_rows;
        // echo $aff;
        if($aff == 1){
            $data = $result -> fetch_object();
            $img = $data->{"DP"};
            // echo $img;
        }
    }

    $profileImgToggler = 
            "<div class='me-2 ms-3' id='userMainImgToggler' style='display:none' > 
                <a href='$profileLink' > <img src='$imagePath/$img' alt='' srcset=''> </a>
            </div>";

    $profileImg = 
            "<div class='me-2 ms-3' id='userMainImg' style='display:none' > 
                <a href='$profileLink' > <img src='$imagePath/$img' alt='' srcset=''> </a>
            </div>";
        
    $navHead = "<a class='navbar-brand' href='$homeLink'>
                    AMU-Forum
                </a>
                <div class='d-flex' id='userMainImgTogglerOut '>
                    <button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbarSupportedContent' aria-controls='navbarSupportedContent' aria-expanded='false' aria-label='Toggle navigation'>
                    <span class='navbar-toggler-icon'></span>
                    </button>
                    $profileImgToggler
                </div>
                ";
}
else{    
    $navHead = "<a class='navbar-brand' href='$homeLink'>AMU-Forum</a>
                <button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbarSupportedContent' aria-controls='navbarSupportedContent' aria-expanded='false' aria-label='Toggle navigation'>
                <span class='navbar-toggler-icon'></span>
                </button>";
}

$navHome = "<li class='nav-item'>
                <a class='nav-link $homeActive' aria-current='page' href='$homeLink'>Home</a>
            </li>";

$profileLink = "<li class='nav-item'>
                <a class='nav-link $profileActive' aria-current='page' href='$profileLink'>Profile</a>
            </li>";
            
$users = "<li class='nav-item'>
            <a class='nav-link $allUserActive' aria-current='page' href='$allUser'>Users</a>
        </li>";


$navSignIn = "<li class='nav-item'>
                <a class='nav-link $signInActive' href='$signIn'>Sign-in</a>
            </li>";

$navContactUs = "<li class='nav-item'>
                    <a class='nav-link $contactUsActive' href='$contactUs'>Contact us</a>
                </li>";

$navAboutUs = "<li class='nav-item'>
                  <a class='nav-link $aboutUsActive' href='$aboutUs'>About us</a>
                </li>";

$navLogOut = "<li class='nav-item'>
                  <a class='nav-link' href='$logOut'>Log-out</a>
                </li>";

$navDropDown = "<li class='nav-item dropdown'>
                    <a class='nav-link dropdown-toggle' href='#' id='navbarDropdown' role='button' data-bs-toggle='dropdown' aria-expanded='false'>
                    Useful Link
                    </a>
                    <ul class='dropdown-menu' aria-labelledby='navbarDropdown'>
                        <li><a class='dropdown-item' target = '_blank' href='https://www.amu.ac.in'>Official Website</a></li>
                        <li><a class='dropdown-item' target='_blank' href='https://www.amucontrollerexams.com/'>Controller Website</a></li>
                        <li><a class='dropdown-item' target='_blank'  href='http://blogamu.epizy.com' >Blog Amu</a></li>
                        <li><a class='dropdown-item' target='_blank'  href='https://www.instagram.com/_ffaheem' >Instagram</a></li>
                    </ul>
                </li>";

$secretLink = " <a href = '$admin' id='secretLink' > ADMIN </a> ";


if(!$boolLoggedIn){

    
    echo "  $secretLink
            <nav class='navbar navbar-expand-lg navbar-dark bg-dark'>
            <div class='container-fluid'>
            $navHead
            <div class='collapse navbar-collapse' id='navbarSupportedContent'>
            <ul class='navbar-nav me-auto mb-2 mb-lg-0'>
                $navHome
                $navSignIn
                $navAboutUs
                $navContactUs
                $navDropDown  
            </ul>
            $navSearch
            </div>
            </div>
            </nav> ";
    
}
else{
    echo "  $secretLink
            <nav class='navbar navbar-expand-lg navbar-dark bg-dark'>
            <div class='container-fluid'>
            $navHead
            <div class='collapse navbar-collapse' id='navbarSupportedContent'>
            <ul class='navbar-nav me-auto mb-2 mb-lg-0'>
                $navHome
                $profileLink  
                $users
                $navAboutUs
                $navContactUs
                $navDropDown
                $navLogOut  
            </ul>
            $navSearch 
            $profileImg
            </div>
            </div>
            </nav>
            ";

}

?>
