<?php 
include('main/database.php');
$order_id = $_POST['order_id'];

$stmt = $con->prepare("SELECT sales_history.*, admins.name as operator FROM sales_history LEFT JOIN admins ON sales_history.emp_id = admins.id WHERE order_id = $order_id");

if($stmt->execute()) {
	$fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode(['text'=>true,'result' => $fetch]);
} else {
	echo json_encode(['text'=>false]);
}
?>