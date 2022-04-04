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
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $category = $_POST["category"];
        $category = $conn->real_escape_string($category);

        // echo $category;

        if($category != ""){
            $sql = "INSERT INTO categories (`category`) VALUES (?)";
            $stmt = $conn->prepare($sql);
          
            $stmt->bind_param("s", $category);
            $stmt->execute();
            $aff = $conn -> affected_rows;
            if($stmt){
                
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
                
                echo $allCategory;


            }else{
                echo "fail";
            }
        }
    }
}

?>
