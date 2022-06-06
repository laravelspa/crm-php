<?php
include '../main/database.php';
    
    if(isset($_POST['id']) && isset($_POST['name']) && isset($_POST['permission'])){
        $id = $_POST['id'];
        $name = $_POST['name'];
        $password = '';
        $newpassword = $_POST['password'];
        $oldpassword = $_POST['oldpassword'];
        if($newpassword !== '') {
            $password = password_hash($newpassword, PASSWORD_DEFAULT);
        } else {
            $password = $oldpassword;
        }
        $permission = $_POST['permission'];
        $supervisor = $_POST['supervisor'] === 'null' ? NULL : $_POST['supervisor'];
        $location = $_POST['permission'] !== '5' ? NULL : $_POST['location'];
        $wall = $_POST['wall'];
        if($permission !== '2') {
            $projects = NULL;
        } else {
            $projects = $_POST['projects'];
        }
        
        $stmt = $con->prepare("UPDATE admins SET name = :name,password = :password, permission = :permission, supervisor = :supervisor, wall=:wall, projects=:projects, location = :location WHERE id = :id");
        $params = array(
            'id' => $id,
            'name' => $name,
            'password' => $password,
            'permission' => $permission,
            'supervisor' => $supervisor,
            'wall' => $wall,
            'projects' => $projects,
            'location' => $location
        );
        
        if ($stmt->execute($params)) {
		    $id = '';
		    $name = '';
		    $oldpassword = '';
		    $newpassword = '';
		    $password = '';
		    $permission = '';
		    $supervisor = '';
		    $projects = '';
            $location = '';
		    echo json_encode(["text" => true,'post'=>$_POST,'params'=>$params]);
        } else {
            echo json_encode(["text" => false,'post'=>$_POST,'params'=>$params]);
        }
    }
?>
