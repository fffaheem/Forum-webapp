<?php
include "../partials/conn.php";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include "../partials/linkAndMeta.php"; ?>    
    <title>Contact-us</title>

    <link rel="stylesheet" href="../static/contactUs.css">
    <script defer src="./contactUs.js"></script>

</head>
<body>
    <?php include "../partials/navbar.php" ?>
    <!-- notification -->
    <?php include "../partials/notification.php" ?>
    
    <div class="container-sm my-4 mt-5">
        <h3 class="text-center mt-5">Send us a message</h3>
        
        
        <div class="container my-3" id="contactUsFormOut">
            
            <div class="my-5" id="alert">
                
            </div>

            <form class="form-floating my-5" id="contactUsForm">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">      
                    <label for="message" class="form-label">Message</label>
                    <textarea class="form-control" placeholder="Type in your message" id="message" name="message" style="height: calc(30vh)" required></textarea>     
                </div>
 
                <div>
                    <button type="submit" class="btn btn-primary">Send</button>
                </div>
            </form>

        </div>

    </div>
</body>
</html>