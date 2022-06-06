<?php ob_start();
    session_start();
    $getName = $_SESSION['project'];
    include('../main/database.php');
    function get_total_one_project_dublicate_records() {
        ob_start();
        session_start();
        $getName = $_SESSION['project'];
        include('../main/database.php');
        $query = "SELECT id FROM pending_dublicate WHERE pname ='". $getName."'";
        $stmt = $con->prepare($query);
        $stmt->execute();
        return $stmt->rowCount();   
    }
    $columnName = [
        1 => 'pending_dublicate.id',
        2 => 'pending_dublicate.name',
        3 => 'pending_dublicate.phone',
        4 => 'pending_dublicate.address',
        5 => 'pending_dublicate.prname',
        6 => 'pending_dublicate.prpieces',
        7 => 'pending_dublicate.prprice',
        8 => 'pending_dublicate.emp_id',
        9 => 'pending_dublicate.doo',
        10 => 'pending_dublicate.status',
        11 => 'pending_dublicate.created_at'
    ];
    $query = '';
    $output = [];
    $query .= "SELECT pending_dublicate.*, admins.name as operator FROM pending_dublicate LEFT JOIN admins ON pending_dublicate.emp_id = admins.id WHERE pending_dublicate.pname ='". $getName."'";

    $emp_id = $_POST['emp_id'];
    $status = $_POST['status'];
    $date_first = $_POST['date_first'];
    $date_last = $_POST['date_last'];
    
    $emp = '';
    if(isset($emp_id) && !empty($emp_id) && $emp_id !== 'NULL') {
        $emp = " AND emp_id = '".$emp_id."'";
    }
    if($emp_id === 'NULL') {
        $emp = " AND emp_id IS NULL";
    } 

    $st = '';
    if(!empty($status) && $status !== 'NULL') {
        $st = " AND status = '".$status."'";
    }

    $and = '';
    if($date_first !== '' && $date_last !== '') {
        $and = " AND DATE(created_at) between '". $date_first ."' and '".$date_last."'" ;
    }

    $search = '';
    if(isset($_POST['search']['value']) && $_POST['search']['value'] != '') {
        $search .= " AND (pending_dublicate.id REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR pending_dublicate.name REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR pending_dublicate.phone REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR pending_dublicate.address REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR pending_dublicate.prname REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR pending_dublicate.prprice REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR pending_dublicate.prpieces REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR pending_dublicate.prcurrency REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR pending_dublicate.doo REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR pending_dublicate.status REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR pending_dublicate.created_at REGEXP '". $_POST['search']['value'] ."')";
    }
    $order = '';
    if(isset($_POST['order'])) {
        $order = " ORDER BY ". $columnName[$_POST['order'][0]['column']].' '. $_POST['order'][0]['dir'];
    } else {
        $order = " ORDER BY pending_dublicate.created_at DESC";
    }
    $limit = '';
    if ($_POST['length'] != -1) {
        $limit = " LIMIT ". $_POST['start'] . ", " . $_POST['length'];
    }

    $stmt = $con->prepare($query.$emp.$st.$and.$search);
    $stmt->execute();
    $filtered_rows = $stmt->rowCount();
    // echo json_encode($stmt);
    $stmt = $con->prepare($query.$emp.$st.$and.$search.$order.$limit);
    $stmt->execute();
    $fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $data = [];
    
    foreach ($fetch as $row) {
        $sub_array = [];
        $sub_array[] = '<input type="checkbox" class="checkedList" name="checkedList" value="'.$row['id'].'"/>';
        $sub_array[] = $row['id'];
        $sub_array[] = $row['name'];
        $sub_array[] = $row['phone'];
        $sub_array[] = $row['address'];
        $sub_array[] = $row['prname'];
        $sub_array[] = $row['prpieces'];
        $sub_array[] = '<span class="badge badge-danger badge-pill p-2">'.$row['prprice'] . ' ' . $row['prcurrency'].'</span>';
        $sub_array[] = $row['operator'];
        $sub_array[] = $row['doo'];
        $sub_array[] = $row['status'];
        $sub_array[] = $row['created_at'];
        $sub_array[] = '<button id="history" class="btn btn-sm btn-outline-primary" 
                            data-id="'.$row['id'].'"
                            data-toggle="modal" data-target="#HistoryModal">
                            <i class="fas fa-eye"></i>
                        </button>';
        $data[] = $sub_array;
    }

    $output = [
        'draw'              => intval($_POST['draw']),
        'recordsTotal'      => get_total_one_project_dublicate_records(),
        'recordsFiltered'   => $filtered_rows,
        'data'              => $data
    ];

    echo json_encode($output);
?>
