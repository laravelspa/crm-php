<?php ob_start();
    session_start();
    include('../../main/database.php');    
    function get_total_orderd_records() {
        ob_start();
        session_start();
        include('../../main/database.php');
        $sessionId = $_SESSION['id'];
        $stmt = $con->prepare("SELECT id FROM admins WHERE supervisor = $sessionId AND permission = 4");
        $stmt->execute();
        $emp = [];
        $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($employees as $employee) {
            $empid = $employee['id'];
            $emp[]  = $empid;
            $stmt = $con->prepare("SELECT id FROM admins WHERE supervisor = $empid AND permission = 5 AND location = 0");
            $stmt->execute();
            $empl = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($empl as $value) {
                $emp[] = $value['id'];
            }
        }
        $ids = !isset($_POST['emp_id']) || $_POST['emp_id'] === '' ? implode($emp, "' ,'") : $_POST['emp_id'];
        $query = "SELECT GROUP_CONCAT(orderd_delivery.orderd_id) as d_id, count(*) as count, a.name as sales_delivery, b.name as delivery, orderd_delivery.*,orderd_delivery.status as d_status, orderd.* FROM `orderd_delivery` LEFT JOIN orderd ON orderd.id = orderd_delivery.orderd_id LEFT JOIN admins as a ON a.id = orderd_delivery.emp_call_id LEFT JOIN admins as b ON b.id = orderd_delivery.emp_delivery_id WHERE orderd.city = 'Cairo' AND orderd_delivery.status = 8 AND orderd_delivery.emp_delivery_id IN('" .$ids. "') GROUP BY orderd.phone";
        $stmt = $con->prepare($query);
        $stmt->execute();
        return $stmt->rowCount();   
    }
    $sessionId = $_SESSION['id'];
    $stmt = $con->prepare("SELECT id FROM admins WHERE supervisor = $sessionId AND permission = 4");
    $stmt->execute();
    $emp = [];
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($employees as $employee) {
        $empid = $employee['id'];
        $emp[]  = $empid;
        $stmt = $con->prepare("SELECT id FROM admins WHERE supervisor = $empid AND permission = 5 AND location = 0");
        $stmt->execute();
        $empl = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($empl as $value) {
            $emp[] = $value['id'];
        }
    }
    $ids = !isset($_POST['emp_id']) || $_POST['emp_id'] === '' ? implode($emp, "' ,'") : $_POST['emp_id'];
    $columnName = [
        1 => 'orderd_delivery.orderd_id',
        2 => 'orderd_delivery.approved',
        3 => 'b.name',
        4 => 'orderd_delivery.delivery_date',
        5 => 'orderd_delivery.delivery_time',
        6 => 'orderd.name',
        7 => 'orderd.phone',
        8 => 'orderd.address',
        9 => 'orderd.city',
        10 => 'orderd.comment',
        11 => 'b.name',
        12 => 'orderd_delivery.d_comment'
    ];
    $output = [];
    $query = "SELECT GROUP_CONCAT(orderd_delivery.orderd_id) as d_id, count(*) as count, a.name as sales_delivery, b.name as delivery, orderd_delivery.*,orderd_delivery.status as d_status, orderd.* FROM `orderd_delivery` LEFT JOIN orderd ON orderd.id = orderd_delivery.orderd_id LEFT JOIN admins as a ON a.id = orderd_delivery.emp_call_id LEFT JOIN admins as b ON b.id = orderd_delivery.emp_delivery_id WHERE orderd.city = 'Cairo' AND orderd_delivery.status = 8 AND orderd_delivery.emp_delivery_id IN('" .$ids. "')";
    
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
        $search .= " OR orderd_delivery.delivery_date REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR orderd_delivery.delivery_time REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR orderd.name REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR orderd.phone REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR orderd.city REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR orderd.address REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR orderd.comment REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR b.name REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR a.name REGEXP '". $_POST['search']['value'] ."'";
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

    $stmt = $con->prepare($query.$and.$search.$order.$limit);
    $stmt->execute();
    $fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $data = [];
    
    foreach ($fetch as $row) {
        $sub_array = [];
        $sub_array[] = '<input type="checkbox" class="checkedListapproved" name="checkedList" value="'.$row['d_id'].'"/>';
        $sub_array[] = $row['count'] === '1' ? 'Single' : 'Package';
        $sub_array[] = '<button 
                data-st="9" data-id="'.$row['d_id'].'"
                data-inv="4" data-prn="'.$row['prname'].'"
                data-prpi="'.$row['prpieces'].'"
                data-msg="Send order to finish tab"  
                data-cmsg="Status order not changed"
                id="sendto_btn" class="btn btn-outline-success btn-sm mr-1">
                Approved
                </button>';
        if($row['approved'] === '1') {
            $sub_array[] = '<i class="fas fa-times-circle fa-lg text-danger"></i>'; 
        } else {
            $sub_array[] = '<i class="fas fa-check-circle fa-lg text-success"></i>'; 
        }
        $sub_array[] = $row['delivery'];
        $sub_array[] = $row['delivery_date'];
        $sub_array[] = $row['delivery_time'];
        $sub_array[] = $row['name'];
        $sub_array[] = $row['phone'];
        $sub_array[] = $row['address'];
        $sub_array[] = $row['city'];
        $sub_array[] = $row['comment'];
        $sub_array[] = $row['sales_delivery'];
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
