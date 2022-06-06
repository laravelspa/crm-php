
<?php
include('../main/database.php');
if (isset($_POST['f_value'], $_POST['f_name'])) {
    $f_name = trim($_POST['f_name']);
    $f_value = trim($_POST['f_value']);
    $note = trim($_POST['note']);
    
    $stmt = $con->prepare("INSERT INTO fingerprints(fingerprint_name, fingerprint_value,note,add_date) VALUES(:fname,:fvalue,:n,NOW())");

    $stmt->bindParam("fname", $f_name, PDO::PARAM_STR);
    $stmt->bindParam("fvalue", $f_value, PDO::PARAM_STR);
    $stmt->bindParam("n", $note, PDO::PARAM_STR);
    
    if ($stmt->execute()) {
        $f_name = '';
        $f_value = '';
        $note = '';
        echo json_encode(['text' => true]);
    } else {
        echo json_encode(['text' => false]);
    }
}
?>