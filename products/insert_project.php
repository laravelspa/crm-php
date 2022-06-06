<?php
    include('../main/database.php');
     header('Content-type: text/html; charset=utf-8');
    if(isset($_POST['project_name'],$_POST['product_name'],$_POST['product_price'],$_POST['employee_id'],$_POST['leads'])){
        $first = $_POST['first_time'];
        $name = $_POST['project_name'];
        $new_time1 = date("Y-m-d H:i:s"); // $now + 2 hours
        if($first == 'true') {
            $stmt = $con->prepare("INSERT INTO project_count(name,count,created_at) VALUES('".$name."',0,'".$new_time1."')");
            $stmt->execute();
        }
 
        $true = false;
        $leads = json_decode($_POST['leads']);
        // $count = count(array_map('unserialize',array_unique(array_map('serialize',$leads)))) - 1;
        for($i=count($leads)-1;$i>0;$i--) {
            $name        = $leads[$i][0]; 
            $phone       = $leads[$i][1]; 
            $address     = $leads[$i][2]; 
            $pname       = $_POST['project_name'];
            $prname      = $_POST['product_name'];
            $prpieces    = $leads[$i][6] !== null ? $leads[$i][6] : 1;
            $prprice     = $_POST['product_price'];
            $prcurrency  = $_POST['product_currency'];
            $emp_id      = $_POST['employee_id'] === 'null' ? NULL : $_POST['employee_id'];
            $status      = $leads[$i][4] === NULL ? 'None' : $leads[$i][4];
            $doo         = $leads[$i][3] !== NULL ? date("Y-m-d", strtotime($leads[$i][3])) : date('Y-m-d');
            $dod         = $leads[$i][5] !== NULL ? date("Y-m-d", strtotime($leads[$i][5])) : date('Y-m-d');
            $admin       = $_POST['admin_name'];
            $new_time    = date("Y-m-d H:i:s"); // $now + 2 hours
            $null = NULL;
            
            $stmt = $con->prepare("CALL add_project(:name, :phone, :address, :city, :pname, :prname, :prpieces, :prprice, :prcurrency, :emp_id, :status, :pending_comment, :pending_date, :pending_time, :doo, :dod, :created_at, :created_by, :db_id, :lead_from, :lead_id, :web_id, :added_at)");
            
            $stmt->bindParam("name", $name,PDO::PARAM_STR);
            $stmt->bindParam("phone", $phone,PDO::PARAM_STR);
            $stmt->bindParam("address", $address,PDO::PARAM_STR);
            $stmt->bindParam("city", $null,PDO::PARAM_STR);
            $stmt->bindParam("pname", $prname,PDO::PARAM_STR);
            $stmt->bindParam("prname", $prname,PDO::PARAM_STR);
            $stmt->bindParam("prpieces", $prpieces,PDO::PARAM_INT);
            $stmt->bindParam("prprice", $prprice,PDO::PARAM_STR);
            $stmt->bindParam("prcurrency", $prcurrency,PDO::PARAM_STR);
            $stmt->bindParam("emp_id", $emp_id,PDO::PARAM_INT);
            $stmt->bindParam("status", $status,PDO::PARAM_STR);
            $stmt->bindParam("pending_comment", $null,PDO::PARAM_STR);
            $stmt->bindParam("pending_date", $null,PDO::PARAM_STR);
            $stmt->bindParam("pending_time", $null,PDO::PARAM_STR);
            $stmt->bindParam("doo", $doo,PDO::PARAM_STR);
            $stmt->bindParam("dod", $dod,PDO::PARAM_STR);
            $stmt->bindParam("created_at", $new_time,PDO::PARAM_STR);
            $stmt->bindParam("created_by", $admin,PDO::PARAM_INT);
            $stmt->bindParam("db_id", $null,PDO::PARAM_INT);
            $stmt->bindParam("lead_from", $null,PDO::PARAM_STR);
            $stmt->bindParam("lead_id", $null,PDO::PARAM_INT);
            $stmt->bindParam("web_id", $null,PDO::PARAM_STR);
            $stmt->bindParam("added_at", $new_time,PDO::PARAM_STR);

            if($stmt->execute()) {
                $true = true;
            }
        }

        if($i === 0 && $true === true) {
            echo json_encode(['text' => true]); 
            $leads = null;
            $name = '';
            $phone = '';
            $address = '';
            $pname = '';
            $prname = '';
            $prpieces = '';
            $prprice = '';
            $prcurrency = '';
            $emp_id = '';
            $status = '';
            $doo = '';
            $dod = '';
            $admin = '';
        } else {
            echo json_encode(['text' => false]);
            $leads = null;
            $name = '';
            $phone = '';
            $address = '';
            $pname = '';
            $prname = '';
            $prpieces = '';
            $prprice = '';
            $prcurrency = '';
            $emp_id = '';
            $status = '';
            $doo = '';
            $dod = '';
            $admin = '';
        }
    }
?>