<?php
include '../main/database.php';
    
    if(isset($_POST['id'], $_POST['dbname'], $_POST['dbtable'],$_POST['dbuser'], $_POST['dbpassword'], $_POST['networkads'], $_POST['landingurl'], $_POST['prname'], $_POST['prprice'], $_POST['prcurrency'], $_POST['comment'])){
        $id = $_POST['id'];
        $dbname = trim($_POST['dbname']);
        $dbtable = $_POST['dbtable'];
        $dbuser = $_POST['dbuser'];
        $dbpassword = $_POST['dbpassword'];
        $networkads = $_POST['networkads'];
        $landingurl = $_POST['landingurl'];
        $prname = $_POST['prname'];
        $prprice = $_POST['prprice'];
        $prcurrency = $_POST['prcurrency'];
        $comment = $_POST['comment'];
    
        $stmt = $con->prepare("UPDATE databases_connections SET db_name = :dbname, db_table = :dbtable, db_user = :dbuser, db_password = :dbpassword, network_ads = :networkads, landing_url = :landingurl, prname=:prname, prprice=:prprice, prcurrency=:prcurrency, comment = :comment WHERE id = :id");
        $params = array(
            'id' => $id,
            'dbname' => $dbname,
            'dbtable' => $dbtable,
            'dbuser' => $dbuser,
            'dbpassword' => $dbpassword,
            'networkads' => $networkads,
            'landingurl' => $landingurl,
            'prname' => $prname,
            'prprice' => $prprice,
            'prcurrency' => $prcurrency,
            'comment' => $comment
        );
        
        if ($stmt->execute($params) === true) {
		    $id = '';
            $dbname = '';
            $dbtable = '';
            $dbuser = '';
            $dbpassword = '';
            $networkads = '';
            $landingurl = '';
            $prname = '';
            $prprice = '';
            $prcurrency = '';
            $comment = '';
		    echo json_encode(["text" => true]);
        } else {
            echo json_encode(["text" => false]);
        }
    }
?>
