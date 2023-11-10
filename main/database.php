<?php
    $dsn = "mysql:host=localhost;dbname=crm-php;";
    $user = "root";
    $pass = "";
    // $option = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',);
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