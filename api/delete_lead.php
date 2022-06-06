<?php

// Delete Selected In Project One
if(isset($_POST['table'])) {

    if(isset($_POST['ids']) || isset($_POST['id'])) {

        include('../main/database.php');

        $ids = isset($_POST['ids']) ? $_POST['ids'] : $_POST['id'];

        $table = $_POST['table'];
        $timesamp = date('Y-m-d H:m:s');
    	$sql = "UPDATE $table SET deleted_at = '" .$timesamp. "'  WHERE id IN (" . $ids . ") ";
        $stmt = $con->prepare($sql);

    	if($stmt->execute()) {

    	    echo json_encode(['text' => true]);   

    	} else {

    	    echo json_encode(['text' => false]);

    	}

    }

}

?>