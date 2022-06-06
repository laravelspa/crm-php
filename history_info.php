<?php 
include('main/database.php');
$order_id = $_POST['order_id'];
$pending_id = $_POST['pending_id'];
$where = $pending_id !== '' ? " WHERE ( order_id = $order_id OR order_id = $pending_id )" : " WHERE order_id = $order_id";

$stmt = $con->prepare("SELECT sales_history.*, admins.name as operator FROM sales_history INNER JOIN admins ON sales_history.emp_id = admins.id $where");
$stmt->execute();
$fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode(['result' => $fetch, 'stmt' => $stmt, 'pid' => $pending_id]);
?>