<?php ob_start();
    session_start();
    $empID = $_SESSION['id'];
    include('../../main/database.php');    
    function get_total_orderd_records() {
        ob_start();
        session_start();
        $empID = $_SESSION['id']; 
        include('../../main/database.php');
        $query = "SELECT GROUP_CONCAT(orderd_delivery.orderd_id) as d_id, count(*) as count, admins.name as sales_delivery, orderd_delivery.*,orderd_delivery.status as d_status, orderd_delivery.created_at as d_create, orderd.* FROM `orderd_delivery` LEFT JOIN orderd ON orderd.id = orderd_delivery.orderd_id LEFT JOIN admins ON admins.id = orderd_delivery.emp_call_id WHERE AND orderd_delivery.approved != 0 AND orderd_delivery.status = 7 AND orderd_delivery.emp_delivery_id = '" . $empID . "' GROUP BY orderd.phone";
        $stmt = $con->prepare($query);
        $stmt->execute();
        return $stmt->rowCount();   
    }
    $columnName = [
        1 => 'orderd_delivery.orderd_id',
        2 => 'orderd_delivery.delivery_date',
        3 => 'orderd_delivery.delivery_time',
        4 => 'orderd.name',
        5 => 'orderd.phone',
        6 => 'orderd.address',
        7 => 'orderd.city',
        8 => 'sales_delivery',
        9 => 'orderd_delivery.d_comment',
        10 => 'orderd.comment'
    ];
    $output = [];
    $query = "SELECT GROUP_CONCAT(orderd_delivery.orderd_id) as d_id, count(*) as count, admins.name as sales_delivery, orderd_delivery.*,orderd_delivery.status as d_status, orderd_delivery.created_at as d_create, orderd.* FROM `orderd_delivery` LEFT JOIN orderd ON orderd.id = orderd_delivery.orderd_id LEFT JOIN admins ON admins.id = orderd_delivery.emp_call_id WHERE orderd_delivery.approved != 0 AND orderd_delivery.status = 7 AND orderd_delivery.emp_delivery_id = '" . $empID . "'";
    
    $date_first = $_POST['date_first'];
    $date_last = $_POST['date_last'];

    $and = '';
    if(isset($date_first) && isset($date_last) &&  $date_first !== '' && $date_last !== '') {
        $and .= " AND DATE(orderd_delivery.delivery_date) between '". $date_first ."' and '".$date_last."' GROUP BY orderd.phone";
    } else {
        $and .= " AND DATE(orderd_delivery.delivery_date) = '".date('Y-m-d')."' GROUP BY orderd.phone";   
    }

    $search = '';
    if(isset($_POST['search']['value']) && $_POST['search']['value'] != '') {
        $search .= " AND (orderd_delivery.id REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR orderd.name REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR orderd.phone REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR orderd.city REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR orderd.comment REGEXP '". $_POST['search']['value'] ."'";
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

    $stmt = $con->prepare($query.$and.$search);
    $stmt->execute();
    $filtered_rows = $stmt->rowCount();

    $stmt = $con->prepare($query.$and.$search.$order.$limit);
    $stmt->execute();
    $fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $data = [];
    
    foreach ($fetch as $row) {
        $sub_array = [];
        if($row['approved'] === '1') {
            $sub_array[] = '<i class="fas fa-times-circle fa-lg text-danger"></i>'; 
        } else {
            $sub_array[] = '<i class="fas fa-check-circle fa-lg text-success"></i>
            <button data-id="'.$row['d_id'].'" id="invoice_btn" class="btn btn-success btn-sm ml-1">
                <i class="fas fa-file-invoice"></i>
            </button>'; 
        } 
        $sub_array[] = $row['count'] === '1' ? 'Single' : 'Package';
        $sub_array[] = $row['delivery_date'];
        $sub_array[] = $row['delivery_time'];
        $sub_array[] = $row['name'];
        $sub_array[] = $row['phone'];
        $sub_array[] = $row['address'];
        $sub_array[] = $row['city'];
        $sub_array[] = $row['sales_delivery'];
        $sub_array[] = $row['d_comment'];
        $sub_array[] = $row['comment'];
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
