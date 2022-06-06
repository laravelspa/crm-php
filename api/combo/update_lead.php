<?php

// Update Lead in combo
if (isset($_POST['id'], $_POST['name'], $_POST['phone'], $_POST['comment'])) {
    include('../../main/database.php');
    
    $sql = "UPDATE combo SET name = :name, phone = :phone, comment = :comment WHERE id = :id";
    $stmt = $con->prepare($sql);
    
    $stmt->bindParam("id", $_POST['id'], PDO::PARAM_INT);
    $stmt->bindParam("name", $_POST['name'], PDO::PARAM_STR);
    $stmt->bindParam("phone", $_POST['phone'], PDO::PARAM_STR);
    $stmt->bindParam("comment", $_POST['comment'], PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo json_encode(['text' => true]);
    } else {

        echo json_encode(['text' => false]);
    }
}
