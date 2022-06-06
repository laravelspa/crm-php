<?php ob_start();
    session_start();
    $pn        = $_SESSION['pn'];
    $sessionId = $_SESSION['id'];
    include('../../main/database.php');
    function get_my_rows_records() {
        ob_start();
        session_start();
        $pn        = $_SESSION['pn'];
        include('../../main/database.php');
        $query = "SELECT * FROM pending WHERE pname ='". $pn."' AND emp_id IS NULL AND !(status <=> 'dod')";
        $stmt = $con->prepare($query);
        $stmt->execute();
        return $stmt->rowCount();   
    }
    $columnName = [
        0 => 'pending.id',
        1 => 'pending.name',
        2 => 'pending.phone',
        3 => 'pending.prname',
        4 => 'pending.prpieces',
        5 => 'pending.prprice',
        6 => 'pending.id',
        7 => 'pending.status',
        8 => 'pending.pending_comment',
        9 => 'pending.pending_date',
        10 => 'pending.pending_time',
        11 => 'pending.doo',
        12 => 'pending.dod'
    ];
    $query = '';
    $output = [];
    $query .= "SELECT * FROM pending WHERE pname ='". $pn."' AND emp_id IS NULL AND !(status <=> 'dod')";
    
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

    $stmt = $con->prepare($query.$search);
    $stmt->execute();
    $filtered_rows = $stmt->rowCount();

    $stmt = $con->prepare($query.$search.$order.$limit);
    $stmt->execute();
    $fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $data = [];
    
    foreach ($fetch as $row) {
        $date = ($row['pending_date'] == null || $row['pending_date'] == 'none' || $row['pending_date'] == 'None') ? date('Y-m-d') : $row['pending_date'];
        $dod = ($row['dod'] == null || $row['dod'] == 'none' || $row['dod'] == 'None') ? date('Y-m-d') : $row['dod'];
        $time = ($row['pending_time'] == null || $row['pending_time'] == 'none' || $row['pending_time'] == 'None') ? date('g:i A', strtotime("+2 hours")) : $row['pending_time'];
        
        $sub_array = [];
        $sub_array[] = $row['id'];
        $sub_array[] = $row['name'];
        $sub_array[] = $row['phone'];
        $sub_array[] = $row['prname'];
        $sub_array[] = $row['prpieces'];
        $sub_array[] = '<span class="badge badge-danger badge-pill p-2">'.$row['prprice'] . ' ' . $row['prcurrency'].'</span>';
        $sub_array[] = '<button id="orderd" class="btn btn-sm btn-success" 
                            data-id="'.$row['id'].'" 
                            data-dbid="'. $row['db_id'].'" 
                            data-name="'.$row['name'].'"
                            data-phone="'.$row['phone'].'"
                            data-add="'.$row['address'].'"
                            data-city="'.$row['city'].'"
                            data-pn="'.$row['pname'].'"
                            data-prn="'.$row['prname'].'"
                            data-prpi="'.$row['prpieces'].'"
                            data-prp="'.$row['prprice'].'"
                            data-prc="'.$row['prcurrency'].'"
                            data-emp="'.$sessionId.'"
                            data-doo="'.$row['doo'].'"
                            data-dod="'.$dod.'"
                            data-com="'.$row['pending_comment'].'"
                            data-lf="'. $row['lead_from'].'"
                            data-toggle="modal" data-target="#orderdModal">
                                <i class="fas fa-check-circle"></i>
                            </button>
                            <button id="canceld" class="btn btn-sm btn-danger" 
                            data-id="'.$row['id'].'" 
                            data-dbid="'. $row['db_id'].'" 
                            data-name="'.$row['name'].'"
                            data-phone="'.$row['phone'].'"
                            data-add="'.$row['address'].'"
                            data-pn="'.$row['pname'].'"
                            data-prn="'.$row['prname'].'"
                            data-prp="'.$row['prprice'].'"
                            data-prpi="'.$row['prpieces'].'"
                            data-prc="'.$row['prcurrency'].'"
                            data-emp="'.$sessionId.'"
                            data-lf="'. $row['lead_from'].'"
                            data-toggle="modal" data-target="#canceldModal">
                            <i class="fas fa-times"></i>
                            </button>
                            <button id="pending" class="btn btn-sm btn-warning" 
                            data-name="'.$row['name'].'"
                            data-phone="'.$row['phone'].'"
                            data-pn="'.$row['pname'].'"
                            data-prn="'.$row['prname'].'"
                            data-prp="'.$row['prprice'].'"
                            data-prc="'.$row['prcurrency'].'"
                            data-emp="'.$sessionId.'"
                            data-status="'.$row['status'].'"
                            data-id="'.$row['id'].'"
                            data-dbid="'. $row['db_id'].'" 
                            data-com="'.$row['pending_comment'].'"
                            data-date="'.$date.'"
                            data-time="'.$time.'"
                            data-lf="'. $row['lead_from'].'"
                            data-toggle="modal" data-target="#pendingModal">
                            <i class="fas fa-phone"></i>
                            </button>
                            <button id="history" class="btn btn-sm btn-primary" 
                            data-id="'.$row['id'].'"
                            data-toggle="modal" data-target="#HistoryModal">
                            <i class="fas fa-eye"></i>
                            </button>';
        $sub_array[] = $row['status'];
        $sub_array[] = $row['pending_comment'];
        $sub_array[] = $row['pending_date'];
        $sub_array[] = $row['pending_time'];
        $sub_array[] = $row['doo'];
        $sub_array[] = $row['dod'];
        $data[] = $sub_array;
    }

    $output = [
        'draw'              => intval($_POST['draw']),
        'recordsTotal'      => get_my_rows_records(),
        'recordsFiltered'   => $filtered_rows,
        'data'              => $data
    ];

    echo json_encode($output);
?>
