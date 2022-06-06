<?php
include('main/database.php');
// Choose Employee In Project One
if(isset($_POST['table']) && isset($_POST['employee_id'])) {
    if(isset($_POST['ids']) || isset($_POST['id'])) {
        $ids = isset($_POST['ids']) ? $_POST['ids'] : $_POST['id'];
        $table = $_POST['table'];
        $emp = $_POST['employee_id'] === 'null' ? 'NULL' : $_POST['employee_id'];
        $new_time = date("Y-m-d H:i:s"); // $now + 2 hours
        $sql = "UPDATE $table SET emp_id = $emp, created_at = '".$new_time."' WHERE id IN(".$ids.")"; 
        $stmt = $con->prepare($sql);
        if($stmt->execute()) {
            $ids = '';
            $table = '';
            $employee = '';
            echo json_encode(['text' => true]);
        } else {
            echo json_encode(['text' => false]);
        }
    }
}

if(isset($_POST['oldproject_name']) && isset($_POST['editproject_name']) && isset($_POST['editproduct_name']) && isset($_POST['editproduct_price']) && isset($_POST['editproduct_currency'])) {
    $opn = $_POST['oldproject_name'];
    $pn = $_POST['editproject_name'];
    $prn = $_POST['editproduct_name'];
    $prp = $_POST['editproduct_price'];
    $prc = $_POST['editproduct_currency'];

    $stmt = $con->prepare("UPDATE pending SET pname = :pn,prname = :prn, prprice = :prp, prcurrency = :prc WHERE prname = :opn");
    $params = array(
        'opn'   => $opn,
        'pn'    => $prn , 
        'prn'   => $prn,
        'prp'   => $prp,
        'prc'   => $prc
    );
    
    if ($stmt->execute($params) === true) {
	    $opn    = '';
        $pn     = '';
        $prn    = '';
        $prp    = '';
        $prc    = '';
        echo json_encode(["text" => true]);
    }
}

// Update Status By Sub Admin
if(isset($_POST['update_id'])) {
    $id = $_POST['update_id'];
    $stmt = $con->prepare("UPDATE orderd SET status = 1 WHERE id = $id");
    if($stmt->execute()) {
        echo json_encode(['text'=>true]);
    }
}

?>