<?php

include "../../partials/conn.php";
session_start();

$boolAdminLoggedIn = false;

if(isset($_SESSION) and isset($_SESSION["adminLogIn"]) and isset($_SESSION["adminUsername"])){
    $sessionUsername = $_SESSION["adminUsername"];
    $boolAdminLoggedIn = true;
}else{
    header("location: ../index.php");

}

if($boolAdminLoggedIn){
    $sql = "SELECT * FROM `categories`";
    $result = $conn->query($sql);
    $categoriesArr = "";

    $i = 1;
    while($data = $result->fetch_object()){
        $category = $data->{"category"};

        if($i % 2 == 0){
            $color = "list-group-item-success";
        }else{
            $color = "list-group-item-info";
        }
        $categoriesArr .= "<li class='list-group-item $color'> $category </li>";
        $i++;
    }
    
    $allCategory= "  <div class='card'>
                            <ol class='list-group list-group-numbered'>
                                $categoriesArr
                            </ol>
                        </div>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include "../../partials/linkAndMeta.php"; ?>
    <link rel="stylesheet" href="../../static/adminNavbar.css">
    <title>Admin || Add Categories</title>
    <script defer src="./index.js"></script>
</head>
<body>

    <?php
        require "../../partials/adminNavbar.php";
    ?>
    <?php
        require "../../partials/notification.php";
    ?>

    <section class="py-3">
        <div class="container my-5">
            <div id="allCategory">
                <?php
                    echo $allCategory;
                ?>
            </div>

            <div class="my-5 d-flex justify-content-end">
                <div>
                    <button type="button" id="addBtn" class="btn btn-primary">Add Category</button>
                </div>
            </div>
        </div>
    </section>

</body>
</html>