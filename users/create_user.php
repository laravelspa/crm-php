<?php 
    include('../main/database.php');
    if(isset($_POST['name'],$_POST['password'],$_POST['permission'],$_POST['projects'])){
        $name = $_POST['name'];
        $password = $_POST['password'];
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $permission = $_POST['permission'];
        $supervisor = $_POST['supervisor'] === 'null' ? NULL : $_POST['supervisor'];
        $wall = $_POST['wall'];
        $projects = $_POST['projects'] === '' ? NULL : $_POST['projects'];
        $location = '';
        if($permission === '0' || $permission === '3') {
            $supervisor = NULL;
        }
        if($permission === '5') {
            $location = $_POST['location'];
        } else {
            $location = NULL;
        }
        $stmt = $con->prepare("INSERT INTO admins(name,password,permission,supervisor,wall,projects,location) VALUES(:n,:p,:per,:sup,:w,:pr,:lo)");
        
        $stmt->bindParam("n", $name,PDO::PARAM_STR);
        $stmt->bindParam("p", $hashed_password,PDO::PARAM_STR);
        $stmt->bindParam("per", $permission,PDO::PARAM_INT);
        $stmt->bindParam("sup", $supervisor,PDO::PARAM_INT);
        $stmt->bindParam("w", $wall,PDO::PARAM_INT);
        $stmt->bindParam("pr", $projects,PDO::PARAM_STR);
        $stmt->bindParam("lo", $location,PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            $name = '';
            $password = '';
            $hashed_password = '';  
            $permission = '';
            $supervisor = '';
            $wall = '';
            $projects = '';
            $location = '';
            echo json_encode(['text' => true]);
        } else {
            echo json_encode(['text' => false]);
        }
    }
?>