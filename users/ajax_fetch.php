<?php 
	include('../main/database.php');
	$stmt = $con->prepare("SELECT id,name FROM admins WHERE permission = 3");
	$stmt->execute();
	$supervisors = $stmt->fetchAll(PDO::FETCH_ASSOC);

	$stmt = $con->prepare("SELECT id,name FROM admins WHERE permission = 4");
	$stmt->execute();
	$supervisors_assistant = $stmt->fetchAll(PDO::FETCH_ASSOC);


	$stmt = $con->prepare("SELECT id,name FROM admins WHERE permission = 1");
	$stmt->execute();
	$supervisors_call = $stmt->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode(['supervisors'=>$supervisors,'supervisors_assistant'=>$supervisors_assistant,'supervisors_call'=>$supervisors_call]);
 ?>