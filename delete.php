<?php

include('main/database.php');

// Delete Sales Applicants
if(isset($_POST['table']) && $_POST['table'] === 'sales_applicants') {
    if(isset($_POST['ids']) || isset($_POST['id'])) {

        $ids = isset($_POST['ids']) ? $_POST['ids'] : $_POST['id'];

        $table = $_POST['table'];

    	$sql = "UPDATE $table SET deleted_at = NOW() WHERE id IN (" . $ids . ") ";

        $stmt = $con->prepare($sql);

    	if($stmt->execute()) {
    	   echo json_encode(['text' => true]);
        } else {
           echo json_encode(['text' => false]);
        }
    }
}

// Delete Selected In Project One
if(isset($_POST['table']) && $_POST['table'] !== 'sales_applicants') {

    if(isset($_POST['ids']) || isset($_POST['id'])) {

        $ids = isset($_POST['ids']) ? $_POST['ids'] : $_POST['id'];

        $table = $_POST['table'];

    	$sql = "DELETE FROM $table WHERE id IN (" . $ids . ") ";
        
        $stmt = $con->prepare($sql);

    	if($stmt->execute()) {
    	   echo json_encode(['text' => true]);
        } else {
           echo json_encode(['text' => false]);
        }
    }
}

// Delete Project

if(isset($_POST['project_name'],$_POST['table_name'])){

    $name = $_POST['project_name'];

    $table = $_POST['table_name'];

    $sql = "DELETE FROM $table WHERE pname IN ('" . $name . "')";

    $stmt3 = $con->prepare($sql);

    

    if($stmt3->execute()) {

        echo json_encode(['text' => true]);

    }

}

?>