<?php

    $cn = new mysqli("localhost", "root", "", "learn_php");
   
    $deleteId = $_POST['id'];

    $sql = "DELETE FROM `tbl_test1` WHERE id = '$deleteId' ";
    $cn->query($sql);
    // alidation
    $response = array('success' => true, 'message' => 'Record deleted successfully');
    
    header('Content-Type: application/json');
    echo json_encode($response);
?>