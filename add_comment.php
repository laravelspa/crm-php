<?php
include 'database.php';

    if(isset($_POST['id']) && isset($_POST['comment'])){
        $id = $_POST['id'];
        $comment = $_POST['comment'];
        
        $stmt = $con->prepare("UPDATE sales SET comment = :comment WHERE id = :id");
        $params = array(
            'id' => $id,
            'comment' => $comment
        );
        if ($stmt->execute($params) === true) {
		    $comment = '';
		    echo json_encode(["text" => "true"]);
        }
    }

?>
