<?php
// Delete Selected In Project One
if(isset($_POST['table'])) {
    if(isset($_POST['ids']) || isset($_POST['id'])) {
        include('../main/database_generator.php');
        $ids = isset($_POST['ids']) ? $_POST['ids'] : $_POST['id'];
        $table = $_POST['table'];
    	$sql = "DELETE FROM $table WHERE id IN (" . $ids . ") ";
        $stmt = $con1->prepare($sql);
    	if($stmt->execute()) {
    	    echo json_encode(['text' => true]);   
    	} else {
    	    echo json_encode(['text' => false]);
    	}
    }
}
?>