<?php
    $dsn = "mysql:host=localhost;dbname=healthyh_crmtest";
    $user = "healthyh_mahmoud";
    $pass = "mahmoud12345";
    try {
        
        $con = new PDO($dsn, $user, $pass);
        // echo 'Good connection';
    }
    
    catch(PDOException $e) {
        // echo 'failed ' . $e->getMassage(); 
    }
    
    $con->query("SET NAMES utf8");
    
    $con->query("SET CHARACTER SET utf8");

?>