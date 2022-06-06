<?php
    include('main/database.php');  
    // Latest 5 Processes In System
    $stmt = $con->prepare("SELECT sales_history.*,admins.name FROM sales_history INNER JOIN admins ON sales_history.emp_id = admins.id ORDER BY created_at DESC LIMIT 5");
    $stmt->execute();
    $actions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // var_dump($actions);
    echo json_encode(['actions' => $actions]);
?>