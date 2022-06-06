<?php
    include('main/database.php');
    session_start();
    $stmt = $con->prepare("UPDATE admins SET online = 0 WHERE id = :id");
    $stmt->bindParam("id", $_SESSION['id'], PDO::PARAM_STR);
    if($stmt->execute()) {
        if(session_destroy()) {
        header("Location:login.php");
        }
    }
    exit;
?>