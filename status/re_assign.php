<?php 
    include('../main/database.php');
    // Re Assaign Canceled Orders To Employee  
    if(isset($_POST['ids'],$_POST['employee_id'],$_POST['status'],$_POST['admin_name'])) {
        $ids = $_POST['ids'];
        $table = $_POST['table'];
        $stmt = $con->prepare("SELECT * FROM $table WHERE id IN (".$ids.")");
        $stmt->execute();
        $all = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        for($i=count($all)-1;$i>=0;$i--) {
            $name         = $all[$i]['name'];
            $phone        = $all[$i]['phone']; 
            $pname        = $all[$i]['pname'];
            $prname       = $all[$i]['prname'];
            $prprice      = $all[$i]['prprice'];
            $prpieces     = $all[$i]['prpieces'];
            $prcurrency   = $all[$i]['prcurrency'];
            $lead_from    = $all[$i]['lead_from'] !== Null ? $all[$i]['lead_from'] : 'NULL';
            $emp_id       = $_POST['employee_id'] === 'null' ? 'NULL' : $_POST['employee_id'];
            $status       = $_POST['status'] !== 'none' ? $_POST['status'] : 'NULL';
            $doo          = date('Y-m-d');
            $admin        = $_POST['admin_name'];
            $created_at   = date("Y-m-d H:i:s"); // $now + 2 hours
            $id = null;
            if($lead_from) {
                $lead     = explode('_', $all[$i]['lead_from']);
                $from     = $lead[0]; // origin1 / origin2
                $id       = $lead[1]; // Id Primary Key In Terraleads Table
                $lead_id  = $lead[2]; // Lead_id In Terraleads Table
            }
            $null = NULL;
            $stmt = $con->prepare("CALL add_project(:name, :phone, :address, :city, :pname, :prname, :prpieces, :prprice, :prcurrency, :emp_id, :status, :pending_comment, :pending_date, :pending_time, :doo, :dod, :created_at, :created_by, :db_id, :lead_from, :lead_id, :web_id, :added_at)");
            
            $stmt->bindParam("name", $name,PDO::PARAM_STR);
            $stmt->bindParam("phone", $phone,PDO::PARAM_STR);
            $stmt->bindParam("address", $address,PDO::PARAM_STR);
            $stmt->bindParam("city", $city,PDO::PARAM_STR);
            $stmt->bindParam("pname", $pname,PDO::PARAM_STR);
            $stmt->bindParam("prname", $prname,PDO::PARAM_STR);
            $stmt->bindParam("prpieces", $prpieces,PDO::PARAM_INT);
            $stmt->bindParam("prprice", $prprice,PDO::PARAM_STR);
            $stmt->bindParam("prcurrency", $prcurrency,PDO::PARAM_STR);
            $stmt->bindParam("emp_id", $emp_id,PDO::PARAM_INT);
            $stmt->bindParam("status", $status,PDO::PARAM_STR);
            $stmt->bindParam("pending_comment", $pending_comment,PDO::PARAM_STR);
            $stmt->bindParam("pending_date", $pending_date,PDO::PARAM_STR);
            $stmt->bindParam("pending_time", $pending_time,PDO::PARAM_STR);
            $stmt->bindParam("doo", $doo,PDO::PARAM_STR);
            $stmt->bindParam("dod", $doo,PDO::PARAM_STR);
            $stmt->bindParam("created_at", $created_at,PDO::PARAM_STR);
            $stmt->bindParam("created_by", $created_by,PDO::PARAM_INT);
            $stmt->bindParam("db_id", $db_id,PDO::PARAM_INT);
            $stmt->bindParam("lead_from", $lead_from,PDO::PARAM_STR);
            $stmt->bindParam("lead_id", $id,PDO::PARAM_INT);
            $stmt->bindParam("web_id", $null,PDO::PARAM_STR);
            $stmt->bindParam("added_at", $created_at,PDO::PARAM_STR);
            
            if($stmt->execute()) {
                if($i==0) {
                    $sql = "DELETE FROM $table WHERE id IN(". $ids .")";
                    $stmt = $con->prepare($sql);
                    if($stmt->execute()) {
                        $ids = '';
                        echo json_encode(['text' => true]);   
                    }            
                }
            } else {
                echo json_encode(['text' => false]);
            }
        }
    }
