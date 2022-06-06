<?php
include '../main/database.php';

if (isset($_POST['id'], $_POST['name'], $_POST['phone'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $comment = $_POST['comment'];
    $editor = $_POST['editor'];

    $stmt = $con->prepare("UPDATE sales_applicants SET 
        name = :name,
        phone = :phone, 
        comment = :comment, 
        editor = :editor WHERE id = :id");
    
    $stmt->bindParam("id", $id, PDO::PARAM_INT);
    $stmt->bindParam("name", $name, PDO::PARAM_STR);
    $stmt->bindParam("phone", $phone, PDO::PARAM_STR);
    $stmt->bindParam("comment", $comment, PDO::PARAM_STR);
    $stmt->bindParam("editor", $editor, PDO::PARAM_STR);

    if ($stmt->execute()) {
        $id = '';
        $name = '';
        $phone = '';
        $comment = '';
        $editor = '';
        echo json_encode(["text" => $stmt->execute()]);
    } else {
        echo json_encode(["text" => $stmt->execute()]);
    }
}
