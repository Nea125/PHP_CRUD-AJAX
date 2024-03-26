<?php
    $cn = new mysqli("localhost", "root", "", "learn_php");
   
    $cn->set_charset("utf8");// support font khmer
    $editID = $_POST["txt-edit-id"];
    $name = trim( $_POST["txt-name"]);
    $name = $cn->real_escape_string($name);
    $price = $_POST["txt-price"];
    $img = $_POST["txt-img"];
    date_default_timezone_set("Asia/Phnom_Penh");
    $date = date("Y-m-d h:i:s A");
     // check duplicate name
    $sql = "SELECT name FROM tbl_test1 WHERE name='$name' && id != $editID";
    $rs = $cn->query($sql);
    $num = $rs->num_rows;
    if($num>0)
    {
        $msg["dpl"]=true;
       
      
    }
    else{

       if($editID==0) 
       {
       
              // Insert Data
              $sql = "INSERT INTO tbl_test1(name, price,post_date,photo) VALUES ('$name', '$price','$date','$img')";
              $cn->query($sql);
              $msg["id"]=$cn->insert_id;
              $msg["edit"]=false;
              // use when button save is not button tage
            
       }
       else{


            // Update
            $sql = "UPDATE `tbl_test1`SET `name`='$name',`price`='$price',`post_date`='$date',`photo`='$img' WHERE id='$editID'";
            $cn->query($sql);
            $msg["edit"]=true;

       }

       $msg["dpl"]=false;
        
    }
   
   
    $msg["date"]=$date;
    echo json_encode($msg); // response data to server side

    
    
    
   
    
?>
