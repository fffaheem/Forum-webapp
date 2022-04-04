<?php



$questionActive = "";
$feedBackActive = "";
$answerActive = "";
$repliesActive = "";
$manageAdminActive = "";
$manageUsersActive = "";
$categoriesActive = "";
$sendMessageActive = "";

$url = $_SERVER['REQUEST_URI'];
$newUrl = explode("?",$url);
$url = $newUrl[0];
$url = strtolower($url);


$website = "/forumWebsite/admin";

$feedBackLink = "$website/Feedback/";
$homeLink = "$website/../profile/profile.php";
$questionLink = "$website/question/";
$answerLink = "$website/answers/";
$repliesLink = "$website/replies/";
$manageAdminLink = "$website/manageAdmin/";
$manageUsersLink = "$website/manageUsers/";
$sendMessageLink = "$website/sendMessage/";
$categoriesLink = "$website/manageCategories/";
$adminLogOut = "$website/adminLogOut.php";



$search = "
<form class='d-flex' id='search'>
<input class='form-control me-2 bg-info border-dark text-dark' type='search' placeholder='Search' aria-label='Search'>
<button class='btn btn-outline-dark' type='submit'>Search</button>
</form>";

if($url == strtolower($feedBackLink) or $url ==  strtolower( $feedBackLink ."index.php" ) ){
    $feedBackActive = "active";
    $search = "";
}

if($url == strtolower($questionLink) or $url ==  strtolower( $questionLink ."index.php" ) ){
    $questionActive = "active";
}

if($url == strtolower($answerLink) or $url ==  strtolower( $answerLink ."index.php" ) ){
    $answerActive = "active";
}

if($url == strtolower($repliesLink) or $url ==  strtolower( $repliesLink ."index.php" ) ){
    $repliesActive = "active";
}

if($url == strtolower($manageAdminLink) or $url ==  strtolower( $manageAdminLink ."index.php" ) ){
    $manageAdminActive = "active";
    $search = "";
}

if($url == strtolower($manageUsersLink) or $url ==  strtolower( $manageUsersLink ."index.php" ) ){
    $manageUsersActive = "active";
}

if($url == strtolower($sendMessageLink) or $url ==  strtolower( $sendMessageLink ."index.php" ) ){
    $sendMessageActive = "active";
    $search = "";

}

if($url == strtolower($categoriesLink) or $url ==  strtolower( $categoriesLink ."index.php" ) ){
    $categoriesActive = "active";
    $search = "";
}





$adminNavbar = "
<nav class='navbar navbar-expand-xl navbar-light bg-info'>
<div class='container-fluid'>
    <a class='navbar-brand' href='$questionLink'>ADMIN</a>
    <button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbarSupportedContent' aria-controls='navbarSupportedContent' aria-expanded='false' aria-label='Toggle navigation'>
    <span class='navbar-toggler-icon'></span>
    </button>
    <div class='collapse navbar-collapse' id='navbarSupportedContent'>
    <ul class='navbar-nav me-auto mb-2 mb-lg-0'>
        <li class='nav-item'>
        <a class='nav-link text-black' aria-current='page' href='$homeLink'>Home</a>
        </li>
        <li class='nav-item'>
        <a class='nav-link $questionActive text-black' aria-current='page' href='$questionLink'>All Questions</a>
        </li>
        <li class='nav-item'>
        <a class='nav-link $answerActive text-black' aria-current='page' href='$answerLink'>All Answers</a>
        </li>
        <li class='nav-item'>
        <a class='nav-link $repliesActive text-black' aria-current='page' href='$repliesLink'>All Replies</a>
        </li>
        <li class='nav-item'>
        <a class='nav-link $categoriesActive text-black' aria-current='page' href='$categoriesLink'>Add Categories</a>
        </li>
        <li class='nav-item'>
        <a class='nav-link $manageAdminActive text-black' aria-current='page' href='$manageAdminLink'>Manage Admins</a>
        </li>
        <li class='nav-item'>
        <a class='nav-link $manageUsersActive text-black' aria-current='page' href='$manageUsersLink'>Manage Users</a>
        </li>
        <li class='nav-item'>
        <a class='nav-link $sendMessageActive text-black' aria-current='page' href='$sendMessageLink'>Send Message</a>
        </li>
        <li class='nav-item'>
        <a class='nav-link $feedBackActive text-black' aria-current='page' href='$feedBackLink'>Feedbacks</a>
        </li>
        <li class='nav-item'>
        <a class='nav-link text-black' aria-current='page' href='$adminLogOut'>Log-out</a>
        </li>
    </ul>
    $search
    </div>
</div>
</nav>";

echo $adminNavbar;

?>