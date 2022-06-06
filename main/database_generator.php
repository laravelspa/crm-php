<?php
    ob_start();
    session_start();
    $dbname = $_SESSION['dbname'];
    $dbuser = $_SESSION['dbuser'];
    $dbpassword = $_SESSION['dbpassword'];
    
    $dsn1 = "mysql:host=localhost;dbname=$dbname";
	$user1 = 'healthy_cure';
	$pass1 = 'healthycure112#';
    
    try {
        $con1 = new PDO($dsn1, $user1, $pass1);
    }
    catch(PDOException $e) {
        // header('location: error.php');
        echo 'failed ' . $e->getMassage(); 
    }

    $con1->query("SET NAMES utf8");

    $con1->query("SET CHARACTER SET utf8");
?>