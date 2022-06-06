<?php ob_start();
    session_start();
    include('main/database.php');
    function get_my_rows_records() {
        ob_start();
        session_start();
        include('main/database.php');
        $stmt = $con->prepare("SELECT sales_history.*,admins.name FROM sales_history INNER JOIN admins ON sales_history.emp_id = admins.id");
        $stmt->execute();
        return $stmt->rowCount();   
    }
    $columnName = [
        0 => 'id',
        1 => 'name',
        2 => 'action',
        3 => 'comment',
        4 => 'created_at',
    ];
    $query = '';
    $output = [];
    $query .= "SELECT sales_history.*,admins.name FROM sales_history INNER JOIN admins ON sales_history.emp_id = admins.id";
    
    $search = '';
    if(isset($_POST['search']['value']) && $_POST['search']['value'] != '') {
        $search .= " WHERE (sales_history.id REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR admins.name REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR sales_history.action REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR sales_history.comment REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR sales_history.created_at REGEXP '". $_POST['search']['value'] ."')";
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
        $sub_array = [];
        $sub_array[] = $row['id'];
        $sub_array[] = $row['name'];
        if($row['action'] === 'dod' || $row['action'] === 'approved') {
            $sub_array[] = '<span class="badge badge-pill badge-success">'.$row['action'].'</span>';
        } else if ($row['action'] === 'call again' || $row['action'] === 'not answer') {
            $sub_array[] = '<span class="badge badge-pill badge-primary">'.$row['action'].'</span>';
        } else if ($row['action'] === 'cancel') {
            $sub_array[] = '<span class="badge badge-pill badge-danger">'.$row['action'].'</span>';
        } else {
            $sub_array[] = '<span >'.$row['action'].'</span>';
        }
        $sub_array[] = $row['comment'];
        $sub_array[] = $row['created_at'];
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
