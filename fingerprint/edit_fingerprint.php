<?php

// Delete Selected In Project One
if (isset($_POST['id'], $_POST['fname'], $_POST['fvalue'], $_POST['fnote'])) {
    include('../main/database.php');

    $sql = "UPDATE fingerprints SET fingerprint_name = :fna, fingerprint_value = :fv, note = :fn WHERE id = :id";
    $stmt = $con->prepare($sql);
    
    $stmt->bindParam("id", $_POST['id'], PDO::PARAM_INT);
    $stmt->bindParam("fna", $_POST['fname'], PDO::PARAM_STR);
    $stmt->bindParam("fv", $_POST['fvalue'], PDO::PARAM_STR);
    $stmt->bindParam("fn", $_POST['fnote'], PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo json_encode(['text' => true]);
    } else {
        echo json_encode(['text' => false]);
    }
}
