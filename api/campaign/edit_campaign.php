<?php

// Delete Selected In Project One
if (isset($_POST['id'], $_POST['cid'], $_POST['cname'], $_POST['cprname'], $_POST['cnote'])) {
    include('../../main/database.php');

    $sql = "UPDATE campaigns SET campaign_id = :cid, campaign_name = :cna, product_name = :prn, note = :cn, status = :st WHERE id = :id";
    $stmt = $con->prepare($sql);
    
    $stmt->bindParam("id", $_POST['id'], PDO::PARAM_INT);
    $stmt->bindParam("cid", $_POST['cid'], PDO::PARAM_STR);
    $stmt->bindParam("cna", $_POST['cname'], PDO::PARAM_STR);
    $stmt->bindParam("prn", $_POST['cprname'], PDO::PARAM_STR);
    $stmt->bindParam("cn", $_POST['cnote'], PDO::PARAM_STR);
    $stmt->bindParam("st", $_POST['cstatus'], PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode(['text' => true]);
    } else {
        echo json_encode(['text' => false]);
    }
}
