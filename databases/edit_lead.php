<?php 
    include('../main/database_generator.php');
    if(isset($_POST['lead_id'],$_POST['lead_name'],$_POST['lead_phone'])) {
        $lead_id = $_POST['lead_id'];
        $lead_name = trim($_POST['lead_name']);
        $lead_phone = trim($_POST['lead_phone']);
        
        $stmt = $con1->prepare("UPDATE sales SET name=:name,phone=:phone WHERE id=:id");
        
        $stmt->bindParam("id", $lead_id,PDO::PARAM_INT);
        $stmt->bindParam("name", $lead_name,PDO::PARAM_STR);
        $stmt->bindParam("phone", $lead_phone,PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            $lead_id = '';
            $lead_name = '';
            $lead_phone = '';
            echo json_encode(['text' => true]);
        } else {
            echo json_encode(['text' => false]);
        }
    }
?>