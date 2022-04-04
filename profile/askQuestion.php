<?php


include "../partials/conn.php";

session_start();
if(!(isset($_SESSION) and isset($_SESSION["username"]))){
  header("location: ../index.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include "../partials/linkAndMeta.php"; ?>    
  <title>Ask Question</title>

    <link rel="stylesheet" href="../static/commonProfile.css">

     <!-- new mini navbar -->
    <link rel="stylesheet" href="../static/miniNavBar.css">
    <link rel="stylesheet" href="../static/profileAskQuestion.css">

    <script defer src="./askQuestion.js"></script>
</head>

<body>

  <!-- navigation Bar -->
  <?php include "../partials/navbar.php";  ?>

  <!-- Mini navBar -->
  <?php include "../partials/miniNavbar.php" ?>

    <!-- notification -->
    <?php include "../partials/notification.php" ?>



  <div class="container mt-4">
      <h2 class="text-center mb-4">Ask a Question</h2>

      <div class="container my-2" id="askFormOut">

        <div class="my-5" id="outcomeMssg">
                
        </div>

        <form class="mb-4" id="askForm">
            <div class="mb-3">
                <label for="qTitle" class="form-label">Question Title</label>
                <input type="text" class="form-control" id="qTitle" name="qTitle" required>
            </div>
            <div class="mb-3">      
                <label for="qDesc" class="form-label">Question Description</label>
                <textarea class="form-control" placeholder="Type in question description" id="qDesc" name="qDesc" style="height: calc(30vh)" required></textarea>     
            </div>
            <div class="mb-3">
                <div class="col">
                    <label for="sort">Category: </label>
                    <select class="form-select" aria-label="Default select example" name="sort">
                        <?php
                            include "../partials/conn.php";
                            $sno = 0;
                            include "../partials/categories.php";
                            echo $options;
                        ?>
                    </select>
                </div>
       
            </div>
            
            <div>
                <button type="submit" class="btn btn-primary">Ask</button>
            </div>
        </form>

    </div>
  </div>
</body>
</html>