<?php
//  include "../partials/conn.php";
 $options = "";
//  $sno = 0;
 $sql = "SELECT * FROM categories";
 $result = $conn->query($sql);
 
 while($data = $result->fetch_object()){
     if($sno==0){

         $options .= "<option selected>".$data->{"category"}."</option>";
     }
     else{
         $options .= "<option value='".$data->{"category"}."' >".$data->{"category"}."</option>";

     }
     $sno++;
     // echo $data->{"category"}."<br>";

 }


?>