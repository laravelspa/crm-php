<?php
    include('../main/database.php');
    if(isset($_POST['product'], $_POST['quantity'], $_POST['inventory'])){
        $product = $_POST['product'];
        $quantity = $_POST['quantity'];
        $inventory = $_POST['inventory'];
        $new_time = date("Y-m-d H:i:s"); // $now + 2 hours
        $stmt = $con->prepare("INSERT INTO products_inventory(product_name,inventory_id,quantity,created_at,updated_at) VALUES(:pro,:inv,:qu,:cr,:up)");

        $stmt->bindParam("pro", $product,PDO::PARAM_STR);
        $stmt->bindParam("qu", $quantity,PDO::PARAM_STR);
        $stmt->bindParam("inv", $inventory,PDO::PARAM_STR);
        $stmt->bindParam("cr", $new_time,PDO::PARAM_STR);
        $stmt->bindParam("up", $new_time,PDO::PARAM_STR);
    
        if($stmt->execute()) {
            $product = '';
            $quantity = '';
            $inventory = '';
            echo json_encode(['text' => true]); 
        } else {
            $product = '';
            $quantity = '';
            $inventory = '';
            echo json_encode(['text' => false]);
        }
    }
?>