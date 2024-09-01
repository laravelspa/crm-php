<?php
$dsn = "mysql:host=localhost;dbname=certs;";
$user = "root";
$pass = "";
// $option = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',);
try {

    $con = new PDO($dsn, $user, $pass);

    $con->query("SET NAMES utf8");

    $con->query("SET CHARACTER SET utf8");
    // echo 'Good connection';
} catch (PDOException $e) {
    // echo 'failed ' . $e->getMassage(); 
}
