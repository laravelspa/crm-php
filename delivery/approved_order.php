<?php ob_start();
    session_start();
    $supervisor = $_SESSION['id'];
    include('../main/database.php');
    // Re Assaign orders To Employee  
    if(isset($_POST['id'],$_POST['approved'])) {
        $id = '"'.implode('","',explode(',',$_POST['id'])).'"';
        $approved = $_POST['approved'];
        $prname = $_POST['prname'];
        $quantity = $_POST['quantity'];
        $inventory = $_POST['inventory'];
        $new_time = date("Y-m-d H:i:s"); // $now + 2 hours
        $dd = date("Y-m-d"); // Delivery date
        $stmt = $con->prepare('UPDATE orderd_delivery SET approved=:approved, updated_at=:updated_at, delivery_date=:dd WHERE orderd_id IN ('.$id.')');
        $params = [
            // 'id' => $id,
            'approved' => $approved,
            'updated_at' => $new_time,
            'dd' => $dd
        ];
        if($stmt->execute($params)) {
            echo json_encode(['text'=>true]);
            if ($approved === '2' && $inventory !== '') {
                $stmt = $con->prepare('UPDATE orderd SET status = 2 WHERE id IN('.$id.')');
                $stmt->execute();
            }
        } else {
            echo json_encode(['text'=>false]);
        }   
    }
    if(isset($_POST['id'],$_POST['status'])) {
        $ids = '"'.implode('","',explode(',',$_POST['id'])).'"';
        $status = $_POST['status'];
        $prname = $_POST['prname'];
        $quantity = $_POST['quantity'];
        $inventory = $_POST['inventory'];
        $new_time = date("Y-m-d H:i:s"); // $now + 2 hours
        $stmt = $con->prepare('UPDATE orderd_delivery SET status=:status, updated_at=:updated_at WHERE orderd_id IN ('.$ids.')');
        $params = [
            // 'id' => $id,
            'status' => $status,
            'updated_at' => $new_time
        ];
        if($stmt->execute($params)) {
            echo json_encode(['text'=>true]);
            if ($status === '9') {
                $stmt = $con->prepare('UPDATE orderd SET status = :status WHERE id IN('.$ids.')');
                $params = [
                    'status' => $inventory,
                ];
                $stmt->execute($params);
            }
        } else {
            echo json_encode(['text'=>false]);
        }   
    }
?>