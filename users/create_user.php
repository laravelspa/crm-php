<?php 
    include('../main/database.php');
    if(isset($_POST['name'],$_POST['password'],$_POST['is_admin'])){
        $name = $_POST['name'];
        $password = $_POST['password'];
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $is_admin = $_POST['is_admin'];
        
        $stmt = $con->prepare("INSERT INTO users(name,password,is_admin) VALUES(:n,:p,:adm)");
        
        $stmt->bindParam("n", $name,PDO::PARAM_STR);
        $stmt->bindParam("p", $hashed_password,PDO::PARAM_STR);
        $stmt->bindParam("adm", $is_admin,PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            $name = '';
            $password = '';
            $hashed_password = '';  
            $is_admin = '';
            echo json_encode(['text' => true]);
        } else {
            echo json_encode(['text' => false]);
        }
    }
?>