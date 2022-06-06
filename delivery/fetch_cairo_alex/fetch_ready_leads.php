<?php ob_start();
    session_start(); 
    $permission = $_SESSION['permission'];
    include('../../main/database.php');    
    function get_total_orderd_records() {
        ob_start();
        session_start();
        $empIDs = "WHERE supervisor = ".$_SESSION['id']." OR id = ". $_SESSION['id'];
        include('../../main/database.php');
        $stmt = $con->prepare("SELECT id FROM admins $empIDs");
        $stmt->execute();
        $fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $ids = [];
        foreach ($fetch as $value) {
            $ids[] = $value['id'];
        }
        $imp = !isset($_POST['emp_id']) || $_POST['emp_id'] === '' ? implode($ids, "' ,'") : $_POST['emp_id'];
        $query = "SELECT GROUP_CONCAT(orderd_delivery.orderd_id) as d_id, count(*) as count, orderd_delivery.*,orderd_delivery.status as d_status, orderd.* FROM `orderd_delivery` LEFT JOIN orderd ON orderd_delivery.orderd_id = orderd.id WHERE orderd_delivery.status = 6 AND orderd_delivery.emp_call_id IN('".$imp."') GROUP BY orderd.phone";
        $stmt = $con->prepare($query);
        $stmt->execute();
        return $stmt->rowCount();   
    }
    $empIDs = "WHERE supervisor = ".$_SESSION['id']." OR id = ". $_SESSION['id'];
    $stmt = $con->prepare("SELECT id FROM admins $empIDs");
    $stmt->execute();
    $fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $ids = [];
    foreach ($fetch as $value) {
        $ids[] = $value['id'];
    }
    $imp = !isset($_POST['emp_id']) || $_POST['emp_id'] === '' ? implode($ids, "' ,'") : $_POST['emp_id'];
    
    $columnName = [
        1 => 'orderd_delivery.delivery_date',
        2 => 'orderd_delivery.delivery_time',
        3 => 'orderd.name',
        4 => 'orderd.phone',
        5 => 'orderd.address',
        6 => 'orderd.city',
        7 => 'orderd.comment',
        8 => 'orderd_delivery.d_comment'
    ];
    $output = [];
    $query = "SELECT admins.name as employee, GROUP_CONCAT(orderd_delivery.orderd_id) as d_id, count(*) as count, orderd_delivery.*,orderd_delivery.status as d_status, orderd_delivery.created_at as d_create, orderd.* FROM orderd_delivery LEFT JOIN admins ON orderd_delivery.emp_call_id = admins.id LEFT JOIN orderd ON orderd_delivery.orderd_id = orderd.id WHERE orderd_delivery.status = 6 AND orderd_delivery.emp_call_id IN('".$imp."')";
    
    $date_first = $_POST['date_first'];
    $date_last = $_POST['date_last'];
    $city = $_POST['city'];
    
    $ci = '';
    if(isset($city) && !empty($city) && $city !== 'NULL') {
        $ci = " AND city = '".$city."'";
    }

    $and = '';
    if(isset($date_first) && isset($date_last) &&  $date_first !== '' && $date_last !== '') {
        $and .= " AND DATE(orderd_delivery.delivery_date) between '". $date_first ."' and '".$date_last."' GROUP BY orderd.phone" ;
    } else {
        $and .= " AND DATE(orderd_delivery.delivery_date) = '".date('Y-m-d')."' GROUP BY orderd.phone";   
    }

    $search = '';
    if(isset($_POST['search']['value']) && $_POST['search']['value'] != '') {
        $search .= " AND (orderd_delivery.id REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR orderd.name REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR orderd.phone REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR orderd.city REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR orderd.address REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR orderd_delivery.delivery_date REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR orderd_delivery.delivery_time REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR orderd_delivery.d_comment REGEXP '". $_POST['search']['value'] ."')";
    }
    $order = '';
    if(isset($_POST['order'])) {
        $order = " ORDER BY ". $columnName[$_POST['order'][0]['column']].' '. $_POST['order'][0]['dir'];
    } else {
        $order = " ORDER BY orderd_delivery.delivery_date DESC";
    }
    $limit = '';
    if ($_POST['length'] != -1) {
        $limit = " LIMIT ". $_POST['start'] . ", " . $_POST['length'];
    }

    $stmt = $con->prepare($query.$ci.$and.$search);
    $stmt->execute();
    $filtered_rows = $stmt->rowCount();

    $stmt = $con->prepare($query.$ci.$and.$search.$order.$limit);
    $stmt->execute();
    $fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $data = [];
    
    foreach ($fetch as $row) {
        $sub_array = [];
        if($permission === '1') {
        $sub_array[] = '<input type="checkbox" class="checkedListready" name="checkedList" value="'.$row['d_id'].'"/>';
        }
        $sub_array[] = $row['count'] === '1' ? 'Single' : 'Package';
        $sub_array[] = $row['delivery_date'];
        $sub_array[] = $row['delivery_time'];
        $sub_array[] = $row['employee'];
        $sub_array[] = $row['name'];
        $sub_array[] = $row['phone'];
        $sub_array[] = $row['address'];
        $sub_array[] = $row['city'];
        $sub_array[] = $row['comment'];
        $sub_array[] = $row['d_comment'];
        $data[] = $sub_array;
    }

    $output = [
        'draw'              => intval($_POST['draw']),
        'recordsTotal'      => get_total_orderd_records(),
        'recordsFiltered'   => $filtered_rows,
        'data'              => $data
    ];

    echo json_encode($output);
?>
