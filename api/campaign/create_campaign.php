
<?php
include('../../main/database.php');
if (isset($_POST['c_id'], $_POST['c_name'], $_POST['prname'], $_POST['status'])) {
    $c_id = trim($_POST['c_id']);
    $c_name = trim($_POST['c_name']);
    $pr_name = trim($_POST['prname']);
    $note = trim($_POST['note']);
    $status = trim($_POST['status']);
    
    $stmt = $con->prepare("INSERT INTO campaigns(campaign_id,campaign_name,product_name,note,status,add_date) VALUES(:cid,:cname,:prn,:n,:st,NOW())");

    $stmt->bindParam("cid", $c_id, PDO::PARAM_STR); 
    $stmt->bindParam("cname", $c_name, PDO::PARAM_STR);
    $stmt->bindParam("prn", $pr_name, PDO::PARAM_STR); 
    $stmt->bindParam("n", $note, PDO::PARAM_STR);
    $stmt->bindParam("st", $status, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        $c_id = '';
        $c_name = '';
        $pr_name = '';
        $note = '';
        $status = '';
        echo json_encode(['text' => true]);
    } else {
        echo json_encode(['text' => false]);
    }
}
?>