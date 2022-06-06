<?php 
	include('../main/database.php');
	if(isset($_POST['ids'],$_POST['table'], $_POST['wall'])) {
		$table = $_POST['table'];
		$ids = $_POST['ids'];
		$wall = $_POST['wall'];
		$stmt = $con->prepare("UPDATE $table SET wall = '".$wall."' WHERE id IN (".$ids.")");

		if($stmt->execute()) {
            $table = '';
            $ids = '';
            $wall = '';
            echo json_encode(['text' => true]);
        }
	}
?>