<?php

// Update Lead in terraleads
if (isset($_POST['id'], $_POST['name'], $_POST['country'], $_POST['phone'], $_POST['address'])) {
    include('../main/database.php');

    $sql = "UPDATE terraleads SET name = :name, country = :country, phone = :phone, address = :address WHERE id = :id";
    $stmt = $con->prepare($sql);
    
    $stmt->bindParam("id", $_POST['id'], PDO::PARAM_INT);
    $stmt->bindParam("name", $_POST['name'], PDO::PARAM_STR);
    $stmt->bindParam("country", $_POST['country'], PDO::PARAM_STR);
    $stmt->bindParam("phone", $_POST['phone'], PDO::PARAM_STR);
    $stmt->bindParam("address", $_POST['address'], PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo json_encode(['text' => true]);
    } else {
        echo json_encode(['text' => false]);
    }
} else {
    echo json_encode(['text' => false]);
}
