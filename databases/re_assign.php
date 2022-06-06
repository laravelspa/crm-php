<?php ob_start();
session_start();
$db_table = $_SESSION['dbtable'];
include('../main/database.php');
include('../main/database_generator.php');
// Choose Employee In Project One

if(isset($_POST['db_id']) && isset($_POST['employee_id'])) {
    if(isset($_POST['ids']) || isset($_POST['id'])) {
        $table = $_POST['table'];
        if($db_table !== '') {
            $ids = isset($_POST['ids']) ? $_POST['ids'] : $_POST['id'];
            // $emp = $_POST['employee_id'] === 'null' ? NULL : $_POST['employee_id'];
            $sql = "SELECT * FROM $db_table WHERE id IN(".$ids.")";
            $stmt = $con1->prepare($sql);
            $stmt->execute();
            $leads = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $counter = count($leads);
            if($counter > 0) {
                foreach($leads as $lead) {
                    $counter--;
                    $name = $lead['name'];
                    $phone = $lead['phone'];
                    $lead_from = $lead['lead_from'];
                    
                    $country = $lead['country'] !== NULL ? $lead['country'] : NULL;
                    $address = $lead['address'] !== NULL ? $lead['address'] : NULL;
                    $pname       = $_POST['project_name'];
                    $prname      = $_POST['product_name'];
                    $prprice     = $_POST['product_price'];
                    $prpieces     = 1;
                    $prcurrency  = $_POST['product_currency'];
                    $emp_id      = $_POST['employee_id'] === 'null' ? NULL : $_POST['employee_id'];
                    $doo = date('Y-m-d');
                    $dod = date('Y-m-d');
                    $admin  = $_POST['admin_id'];
                    $dbid  = $_POST['db_id'];
                    $new_time = date("Y-m-d H:i:s"); // $now + 2 hours
                    $status = 'NEW';
                    $null = NULL;
                    $added_at = $lead['order_at'] ?? NULL;

                    // $stmt = $con->prepare("CALL add_project('{$name}', '{$phone}', 
                    // '{$address}', '{$country}','{$pname}','{$prname}', '{$prpieces}', 
                    // '{$prprice}', '{$prcurrency}', 
                    // '{$emp_id}', 'New', '{$null}', '{$null}', '{$null}', '{$doo}', '{$dod}', 
                    // '{$new_time}', '{$admin}', '{$dbid}', '{$lead_from}', {$null}, '{$web_id}', '{$added_at}')");
                    
                    $stmt = $con->prepare("CALL add_project(:name, :phone, :address, :city, :pname, :prname, :prpieces, :prprice, :prcurrency, :emp_id, :status, :pending_comment, :pending_date, :pending_time, :doo, :dod, :created_at, :created_by, :db_id, :lead_from, :lead_id, :web_id, :added_at)");
                    
                    $stmt->bindParam("name", $name,PDO::PARAM_STR);
                    $stmt->bindParam("phone", $phone,PDO::PARAM_STR);
                    $stmt->bindParam("address", $address,PDO::PARAM_STR);
                    $stmt->bindParam("city", $country,PDO::PARAM_STR);
                    $stmt->bindParam("pname", $pname,PDO::PARAM_STR);
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
                    $stmt->bindParam("db_id", $dbid,PDO::PARAM_INT);
                    $stmt->bindParam("lead_from", $lead_from,PDO::PARAM_STR);
                    $stmt->bindParam("lead_id", $null,PDO::PARAM_INT);
                    $stmt->bindParam("web_id", $null,PDO::PARAM_STR);
                    $stmt->bindParam("added_at", $added_at,PDO::PARAM_STR);

                    if($stmt->execute()) {
                        if($counter === 0) {
                            $sql = "DELETE FROM $db_table WHERE id IN(".$ids.")";
                            $stmt = $con1->prepare($sql);
                            if($stmt->execute()) {
                                echo json_encode(["text" => true]);   
                            }
                        }
                    }     
                }
            } else {
                echo json_encode(['text' => false]);
            }   
        }
    }
}




