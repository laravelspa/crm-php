<?php

// Delete Selected In Project One
if(isset($_POST['table'])) {

    if(isset($_POST['ids']) || isset($_POST['id'])) {

        include('../main/database.php');

        $ids = isset($_POST['ids']) ? $_POST['ids'] : $_POST['id'];

        $table = $_POST['table'];
        
    	//   $sql = "UPDATE $table SET deleted_at = NOW()  WHERE id IN (:ids)";
    	  $sql = "DELETE FROM $table  WHERE id IN (:ids)";
        $stmt = $con->prepare($sql);
        $stmt->bindParam("ids", $ids, PDO::PARAM_INT);

    	if($stmt->execute()) {

    	    echo json_encode(['text' => true]);   

    	} else {

    	    echo json_encode(['text' => false]);

    	}

    }

}
