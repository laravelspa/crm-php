<?php 
include '../main/database.php';
if(isset($_POST['product_name'],$_POST['product_quantity'],$_POST['product_id'],$_POST['inventory'])) {
	$product_name = $_POST['product_name'];
	$product_quantity = $_POST['product_quantity'];
	$product_id = $_POST['product_id'];
	$inventory = $_POST['inventory'];
	$updated_at = date("Y-m-d H:i:s"); // $now + 2 hours
	$stmt = $con->prepare("UPDATE products_inventory SET product_name = :product_name, quantity = :product_quantity, updated_at = :updated_at WHERE id = :product_id AND inventory_id = :inventory");
	$params = [
		'product_name' => $product_name,
		'product_quantity' => $product_quantity,
		'product_id' => $product_id,
		'inventory' => $inventory,
		'updated_at' => $updated_at
	];
	if($stmt->execute($params)) {
		echo json_encode(['text'=>true]);
	} else {
		echo json_encode(['text'=>false]);
	}

}


 ?>