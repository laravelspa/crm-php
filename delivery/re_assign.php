<?php ob_start();
    session_start();
    $supervisor = $_SESSION['id'];
    include('../main/database.php');
    // Re Assaign orders To Employee  
    if(isset($_POST['ids'],$_POST['employee_id'],$_POST['table'])) {
        $ids = explode(',', $_POST['ids']);
        $table = $_POST['table'];
        $emp = $_POST['employee_id'];
        $activeTab = $_POST['activeTab'];
        $waitingdate = $_POST['waitingdate'] === null ? date('Y-m-d') : $_POST['waitingdate'];
        $d_status = $_POST['d_status'];
        $new_time = date("Y-m-d H:i:s"); // $now + 2 hours
        if($activeTab === '#stage') {
            for ($i=count($ids)-1; $i >= 0; $i--) { 
                $stmt = $con->prepare("UPDATE orderd SET status = 1 WHERE id = '".$ids[$i]."'");
                if($stmt->execute()) {
                    $stmt = $con->prepare("INSERT INTO orderd_delivery(orderd_id,status,emp_call_id,delivery_date,approved,created_at,updated_at) VALUES(:orderd_id,:status,:emp_call_id,:waitingdate,:approved,:created_at,:updated_at)");
                    $params = [
                        'orderd_id' => $ids[$i],
                        'status' => $d_status,
                        'waitingdate' => $waitingdate,
                        'approved' => 0,
                        'created_at' => $new_time,
                        'updated_at' => $new_time,
                        'emp_call_id' => $emp
                    ];
                    if($stmt->execute($params)) {
                        if($i === 0) {
                            echo json_encode(['text'=>true]);
                        }
                    } else {
                        echo json_encode(['text'=>false]);
                    }
                }
            }
        } else {
            for ($i=count($ids)-1; $i >= 0; $i--) { 
                $stmt = $con->prepare("UPDATE orderd_delivery SET delivery_date=:dd, emp_call_id=:emp_call_id, status=:status, updated_at=:updated_at, approved=:approved WHERE orderd_id=:orderd_id");
                $params = [
                    'orderd_id' => $ids[$i],
                    'dd' => $waitingdate,
                    'updated_at' => $new_time,
                    'emp_call_id' => $emp,
                    'status' => $d_status,
                    'approved' => 0
                ];
                // echo json_encode(['text'=>true,'stmt'=>$stmt,'params'=>$params]);
                if($stmt->execute($params)) {
                    if($i === 0) {
                        echo json_encode(['text'=>true,'params'=>$params]);
                    }
                } else {
                    echo json_encode(['text'=>false]);
                }   
            }
        }        
    }

    // Re assign to assistant
    if(isset($_POST['ids'],$_POST['assistant'],$_POST['table'])) {
        $ids = explode(',', $_POST['ids']);
        $table = $_POST['table'];
        $ass = $_POST['assistant'];
        $new_time = date("Y-m-d H:i:s"); // $now + 2 hours
       for ($i=count($ids)-1; $i >= 0; $i--) { 
            $stmt = $con->prepare("UPDATE orderd_delivery SET emp_delivery_id =:emp_delivery_id, updated_at=:updated_at WHERE orderd_id=:orderd_id");
            $params = [
                'orderd_id' => $ids[$i],
                'updated_at' => $new_time,
                'emp_delivery_id' => $ass
            ];
            if($stmt->execute($params)) {
                if($i === 0) {
                    echo json_encode(['text'=>true,'post'=>$_POST,'stmt'=>$stmt]);
                }
            } else {
                echo json_encode(['text'=>false]);
            }   
        }        
    }

     // Re assign to delivery man
    if(isset($_POST['ids'],$_POST['delivery_man'],$_POST['table'])) {
        $ids = explode(',', $_POST['ids']);
        $table = $_POST['table'];
        $dm = $_POST['delivery_man'];
        $status = 7;
        $new_time = date("Y-m-d H:i:s"); // $now + 2 hours
       for ($i=count($ids)-1; $i >= 0; $i--) { 
            $stmt = $con->prepare("UPDATE orderd_delivery SET status = :status, emp_delivery_id =:emp_delivery_id, updated_at=:updated_at WHERE orderd_id=:orderd_id");
            $params = [
                'orderd_id' => $ids[$i],
                'updated_at' => $new_time,
                'emp_delivery_id' => $dm,
                'status' => $status
            ];
            if($stmt->execute($params)) {
                if($i === 0) {
                    echo json_encode(['text'=>true,'post'=>$_POST,'stmt'=>$stmt]);
                }
            } else {
                echo json_encode(['text'=>false]);
            }   
        }        
    }
?>