<?php
    include('/main/database.php');
    
    $keys = implode(',',array_keys($_POST));
    $v = '';
    foreach(array_values($_POST) as $val) {
        $v .= "'" . $val . "',";
    }
    $values = rtrim($v, ',');
    
    $date = date("Y-m-d");
    $time = date("H:m:s");
    $stmt = "INSERT INTO sales(". $keys .",date,time) VALUES(". $values .",'" . $date . "', '" . $time . "')";
    
    $st = $con->prepare($stmt);
    
    if($st->execute()) {
        header('Location:thanks.php');
    } else {
         echo json_encode(['status' => false]);
    }
?>