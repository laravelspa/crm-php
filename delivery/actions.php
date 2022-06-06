<?php
    ob_start(); 
    session_start();
    // var_dump($_POST);
    $supervisor = $_SESSION['id'];
    include('../main/database.php');
    // Take Action Waiting Call From Supervisor  
    if(isset($_POST['orderd_id'],$_POST['status'])) {
        $orderd_id = '"'.implode('","',explode(',',$_POST['orderd_id'])).'"';
        $waitingdate = $_POST['waitingdate'] === 'undefined' ? date('Y-m-d') : $_POST['waitingdate'];
        $waitingtime = $_POST['waitingtime'] === 'undefined' ? NULL : $_POST['waitingtime'];
        $waitingcom = $_POST['waitingcom'] === '' ? NULL : $_POST['waitingcom'];
        $status = $_POST['status'];
        $oldstatus = $_POST['oldstatus'];
        $code = $_POST['code'] === 'undefined' ? NULL : $_POST['code'];;
        $new_time = date("Y-m-d H:i:s"); // $now + 2 hours
        $created_at = $_POST['created_at'] === '' ? $new_time : $_POST['created_at'];
        $stmt = $con->prepare('UPDATE orderd_delivery SET delivery_date=:dd, delivery_time=:dt, d_comment=:dcom, aramex_code=:code, status=:status, created_at=:created_at,updated_at=:updated_at WHERE orderd_id IN('.$orderd_id.')');
        $params = [
            // 'orderd_id' => $orderd_id,
            'dd' => $waitingdate,
            'dt' => $waitingtime,
            'dcom' => $waitingcom,
            'code' => $code,
            'status' => $status,
            'created_at' => $created_at,
            'updated_at' => $new_time
        ];
        if($stmt->execute($params)) {
            echo json_encode(['text'=>true,'stmt1'=>$stmt]);
            
        } else {
            echo json_encode(['text'=>false,'stmt2'=>$stmt,
                'ids'=>$orderd_id]);
        }
    }

    if(isset($_POST['orderd_id'],$_POST['deliverydate'],$_POST['deliverytime'],$_POST['deliverycom'])) {
        $d_id = '"'.implode('","',explode(',',$_POST['d_id'])).'"';
        // $orderd_id = $_POST['orderd_id'];
        // $address = $_POST['address'];
        $deliverydate = $_POST['deliverydate'] === '' ? date('Y-m-d') : $_POST['deliverydate'];
        $deliverytime = $_POST['deliverytime'] === '' ? NULL : $_POST['deliverytime'];
        $deliverycom = $_POST['deliverycom'] === '' ? NULL : $_POST['deliverycom'];
        $new_time = date("Y-m-d H:i:s"); // $now + 2 hours
        $created_at = $_POST['created_at'] === '' ? $new_time : $_POST['created_at'];
        $stmt = $con->prepare('UPDATE orderd_delivery SET delivery_date=:dd, delivery_time=:dt, d_comment=:dcom, created_at=:created_at,updated_at=:updated_at WHERE orderd_id IN('.$d_id.')');
        $params = [
            // 'd_id' => $d_id,
            'dd' => $deliverydate,
            'dt' => $deliverytime,
            'dcom' => $deliverycom,
            'created_at' => $created_at,
            'updated_at' => $new_time
        ];
        if($stmt->execute($params)) {
            // $stmt = $con->prepare("UPDATE orderd SET address=:add WHERE id=:orderd_id");
            // $params = [
            //     'add' => $address,
            //     'orderd_id' => $orderd_id
            // ];
            // if($stmt->execute($params)) {
                echo json_encode(['text'=>true,'stmt3'=>$stmt]);
            // } else {
            //     echo json_encode(['text'=>false]);
            // }
        } else {
            echo json_encode(['text'=>false,'stmt4'=>$stmt]);
        }
    }
?>