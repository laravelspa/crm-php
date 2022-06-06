<?php 
    include('../main/database.php');
    if(isset($_POST['dbname'],$_POST['dbtable'],$_POST['dbuser'],$_POST['dbuserpassword'],$_POST['network_ads'],$_POST['landing_url'],$_POST['prname'],$_POST['prprice'],$_POST['prcurrency'])){
        $dbname = trim($_POST['dbname']);
        $dbtable = $_POST['dbtable'];
        $dbuser = $_POST['dbuser'];
        $dbuserpassword = $_POST['dbuserpassword'];
        $network_ads = $_POST['network_ads'];
        $landing_url = $_POST['landing_url'];
        $prname = $_POST['prname'];
        $prprice = $_POST['prprice'];
        $prcurrency = $_POST['prcurrency'];
        $comment = $_POST['comment'];
    
        $stmt = $con->prepare("INSERT INTO databases_connections(db_name,db_table,db_user,db_password,network_ads,landing_url,prname,prprice,prcurrency,comment) VALUES(:dbn,:dbt,:dbu,:dbp,:nds,:lur,:prname,:prprice,:prcurrency,:com)");
        
        $stmt->bindParam("dbn", $dbname,PDO::PARAM_STR);
        $stmt->bindParam("dbt", $dbtable,PDO::PARAM_STR);
        $stmt->bindParam("dbu", $dbuser,PDO::PARAM_STR);
        $stmt->bindParam("dbp", $dbuserpassword,PDO::PARAM_STR);
        $stmt->bindParam("nds", $network_ads,PDO::PARAM_STR);
        $stmt->bindParam("lur", $landing_url,PDO::PARAM_STR);
        $stmt->bindParam("prname", $prname,PDO::PARAM_STR);
        $stmt->bindParam("prprice", $prprice,PDO::PARAM_STR);
        $stmt->bindParam("prcurrency", $prcurrency,PDO::PARAM_STR);
        $stmt->bindParam("com", $comment,PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            $dbname = '';
            $dbtable = '';
            $dbuser = '';
            $dbuserpassword = '';
            $network_ads = '';
            $landing_url = '';
            $prname = '';
            $prprice = '';
            $prcurrency = '';
            $comment = '';
            echo json_encode(['text' => true]);
        } else {
            echo json_encode(['text' => false]);
        }
    }
?>