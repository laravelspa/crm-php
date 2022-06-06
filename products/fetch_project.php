<?php ob_start();
    session_start();
    $getName = $_SESSION['project'];
    include('../main/database.php');
    function get_total_one_project_records() {
        ob_start();
        session_start();
        $getName = $_SESSION['project'];
        include('../main/database.php');
        $query = "SELECT id FROM pending WHERE pname ='". $getName."'";
        $stmt = $con->prepare($query);
        $stmt->execute();
        return $stmt->rowCount();   
    }
    $columnName = [
        1 => 'pending.id',
        2 => 'pending.name',
        3 => 'pending.phone',
        4 => 'pending.address',
        5 => 'pending.prname',
        6 => 'pending.prpieces',
        7 => 'pending.prprice',
        8 => 'pending.emp_id',
        9 => 'pending.doo',
        10 => 'pending.status',
        11 => 'pending.created_at'
    ];
    $query = '';
    $output = [];
    $query .= "SELECT pending.*, admins.name as operator FROM pending LEFT JOIN admins ON pending.emp_id = admins.id WHERE pending.pname ='". $getName."'";

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
    if($date_first !== '' && $date_last !== '' && $date_first !== $date_last) {
        $and = " AND DATE(created_at) between '". $date_first ."' and '".$date_last."'" ;
    }
    
    if($date_first !== '' && $date_last !== '' && $date_first === $date_last) {
        $and = " AND DATE(created_at) = '".$date_last."'" ;
    }

    $search = '';
    if(isset($_POST['search']['value']) && $_POST['search']['value'] != '') {
        $search .= " AND (pending.id REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR pending.name REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR pending.phone REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR pending.address REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR pending.prname REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR pending.prprice REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR pending.prpieces REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR pending.prcurrency REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR pending.doo REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR pending.status REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR pending.created_at REGEXP '". $_POST['search']['value'] ."')";
    }
    $order = '';
    if(isset($_POST['order'])) {
        $order = " ORDER BY ". $columnName[$_POST['order'][0]['column']].' '. $_POST['order'][0]['dir'];
    } else {
        $order = " ORDER BY created_at DESC";
    }
    $limit = '';
    if ($_POST['length'] != -1) {
        $limit = " LIMIT ". $_POST['start'] . ", " . $_POST['length'];
    }

    $stmt = $con->prepare($query.$emp.$st.$and.$search);
    $stmt->execute();
    $filtered_rows = $stmt->rowCount();
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
        $sub_array[] = '<span class="badge badge-danger badge-pill p-1">'.$row['prprice'] . ' ' . $row['prcurrency'].'</span>';
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
        'recordsTotal'      => get_total_one_project_records(),
        'recordsFiltered'   => $filtered_rows,
        'data'              => $data
    ];

    echo json_encode($output);
?>
