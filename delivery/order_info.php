<?php 
	include '../main/database.php';
	$ids = '"'.implode('","',explode(',',$_POST['order_id'])).'"';
	$stmt = $con->prepare('SELECT prname,prpieces,prprice,prcurrency FROM orderd WHERE id IN ('.$ids.')');
	$stmt->execute();
	$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode(['data'=>$orders]);
 ?>