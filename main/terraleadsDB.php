<?php

  //Connect To Networks
  $dsn = 'mysql:host=localhost;dbname=testdb';
  $user = 'test_user';
  $password = 'test_password';
  $option = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',);
  try {
    	$conNetworks= new PDO($dsn,$user,$password,$option);
       	$conNetworks->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      }
  catch(PDOException $e) {echo 'Failed To Connect';}

?>