<?php 
	include '../main/database.php';
	if(isset($_POST['id'],$_POST['add_quantity'])) {
		$id = $_POST['id'];
		$quantity = $_POST['add_quantity'];
		$stmt = $con->prepare('UPDATE products_inventory SET quantity = quantity + :quantity WHERE id = :id');
		$params = [
			'quantity' => $quantity,
			'id' => $id
		];
		if($stmt->execute($params)) {
			echo json_encode(['text' => true,'stmt'=>$stmt,'post'=>$_POST]);
		} else {
			echo json_encode(['text' => false,'stmt'=>$stmt]);
		}

	}


 ?>