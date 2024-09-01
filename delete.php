<?php

include('main/database.php');

// Delete
if(isset($_POST['table'])) {
    if(isset($_POST['ids']) || isset($_POST['id'])) {
        $table = $_POST['table'];
        $ids = isset($_POST['ids']) ? $_POST['ids'] : $_POST['id'];

    	$sql = "UPDATE $table SET deleted_at = NOW() WHERE id IN (" . $ids . ") ";

        $stmt = $con->prepare($sql);

    	if($stmt->execute()) {
    	   echo json_encode(['text' => true]);
        } else {
           echo json_encode(['text' => false]);
        }
    }
}
?>