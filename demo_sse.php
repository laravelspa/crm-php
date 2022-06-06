<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
 include('main/database.php');  
    // Latest 5 Processes In System
    $stmt = $con->prepare("SELECT sales_history.*,admins.name FROM sales_history INNER JOIN admins ON sales_history.emp_id = admins.id ORDER BY created_at DESC LIMIT 1");
    $stmt->execute();
    $actions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $data = json_encode($actions);
    echo "data:  {$data}\n\n";
// $time = date('r');
// echo "data: The server time is: {$time}\n\n";
flush();
?>