<?php
// ob_start();
// session_start();
include('../../main/database.php');
// Re assign Leads
if (isset($_POST['selected'], $_POST['employee_id'], $_POST['admin_id'])) {
    $ids = $_POST['ids'];
    $leads = $array = json_decode($_POST['selected'], true);
    $emp_id = $_POST['employee_id'];
    $admin_id = $_POST['admin_id'];

    $prnames = [];
    foreach ($leads as $key => $value) {
        $prnames[] = $value['prname'];
    }
    $prnamesUniqe = array_unique($prnames);

    $spread = join("', '", $prnamesUniqe);;
    if ($spread !== '') {
        $sql = "SELECT pname,prname, prprice, prcurrency FROM pending WHERE prname IN ('$spread') GROUP BY prname";
        $stmt = $con->prepare($sql);
        $stmt->execute();
        $productInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $prpieces = 1;
        $status = "NEW";
        $now = date("Y-m-d H:i:s"); // $now + 2 hours
        $null = NULL;
        $doo  = date('Y-m-d');
        $prprice = "200";
        $prcurrency = "EGP";
        
        for ($i = count($leads) - 1; $i >= 0; $i--) {
            $lead_from = "origin2_".$leads[$i]['id']."_".$leads[$i]['lead_id'];
            $sql = "CALL add_project(:name, :phone, :address, :city, :pname, :prname, :prpieces,:prprice, :prcurrency, :emp_id, :status, :pending_comment, :pending_date, :pending_time, :doo, :dod, :created_at, :created_by, :db_id, :lead_from, :lead_id, :user_id, :added_at)";

            $stmt = $con->prepare($sql);
            
            $stmt->bindParam("name", $leads[$i]['name'], PDO::PARAM_STR);
            $stmt->bindParam("phone", $leads[$i]['phone'], PDO::PARAM_STR);
            $stmt->bindParam("address", $leads[$i]['address'], PDO::PARAM_STR);
            $stmt->bindParam("city", $leads[$i]['country'], PDO::PARAM_STR);
            $stmt->bindParam("pname", $leads[$i]['prname'], PDO::PARAM_STR);
            $stmt->bindParam("prname", $leads[$i]['prname'], PDO::PARAM_STR);
            $stmt->bindParam("prpieces", $prpieces, PDO::PARAM_INT);
            
            if(count($productInfo)) {
                foreach ($productInfo as $k => $v) {
                    if ($leads[$i]['prname'] === $v['prname']) {
                        $prprice = $v['prprice'];
                        $prcurrency = $v['prcurrency'];
                    }
                }
            }
            $id = $leads[$i]['id'];
            $user_id = $leads[$i]['user_id'];
            $added_at = $leads[$i]['created_at'];

            $stmt->bindParam("prprice", $prprice, PDO::PARAM_STR);
            $stmt->bindParam("prcurrency", $prcurrency, PDO::PARAM_STR);
            $stmt->bindParam("emp_id", $emp_id, PDO::PARAM_INT);
            $stmt->bindParam("status", $status, PDO::PARAM_STR);
            $stmt->bindParam("pending_comment", $null, PDO::PARAM_STR);
            $stmt->bindParam("pending_date", $null, PDO::PARAM_STR);
            $stmt->bindParam("pending_time", $null, PDO::PARAM_STR);
            $stmt->bindParam("doo", $doo, PDO::PARAM_STR);
            $stmt->bindParam("dod", $null, PDO::PARAM_STR);
            $stmt->bindParam("created_at", $now, PDO::PARAM_STR);
            $stmt->bindParam("created_by", $admin_id, PDO::PARAM_INT);
            $stmt->bindParam("db_id", $null, PDO::PARAM_INT);
            $stmt->bindParam("lead_from", $lead_from, PDO::PARAM_STR);
            $stmt->bindParam("lead_id", $id, PDO::PARAM_INT);
            $stmt->bindParam("user_id", $user_id, PDO::PARAM_STR);
            $stmt->bindParam("added_at", $added_at, PDO::PARAM_STR);

            if ($stmt->execute()) {
                if ($i == 0) {
                    $sql = "UPDATE combo SET deleted_at = NOW() WHERE id IN(" . $ids . ")";
                    $stmt = $con->prepare($sql);
                    if ($stmt->execute()) {
                        $ids = '';
                        echo json_encode(['message' => true]);
                    }
                }
            } else {
                echo json_encode(['message' => false]);
            }
        }
    } else {
        echo json_encode(['message' => false, 'text' => 'Check if campaign is created or not!']);
    }
}
