<?php include('../../main/database.php');
    function get_total_orderd_records() {
        include('../../main/database.php');
        $query = "SELECT GROUP_CONCAT(orderd.id) as ids, count(*) as count, orderd.*, admins.name as sales FROM orderd LEFT JOIN admins ON orderd.emp_id = admins.id WHERE status = 0 AND (city = 'Alexandria' OR city = 'Cairo') GROUP BY orderd.phone";
        $stmt = $con->prepare($query);
        $stmt->execute();
        return $stmt->rowCount();
    }
    $columnName = [
        1 => 'orderd.dod',
        2 => 'orderd.tod',
        3 => 'orderd.name',
        4 => 'orderd.phone',
        5 => 'orderd.address',
        6 => 'orderd.city',
        7 => 'sales',
        8 => 'orderd.comment'
    ];
    $output = [];
    $query = "SELECT GROUP_CONCAT(orderd.id) as ids, count(*) as count, orderd.*, admins.name as sales FROM orderd LEFT JOIN admins ON orderd.emp_id = admins.id WHERE status = 0 AND (city = 'Alexandria' OR city = 'Cairo')";
    
    $date_first = $_POST['date_first'];
    $date_last = $_POST['date_last'];
    $city = $_POST['city'];
    
    $ci = '';
    if(isset($city) && !empty($city) && $city !== 'NULL') {
        $ci = " AND city = '".$city."'";
    }

    $and = '';
    if(isset($date_first) && isset($date_last) &&  $date_first !== '' && $date_last !== '') {
        $and .= " AND dod between '". $date_first ."' and '".$date_last."' GROUP BY orderd.phone";
    } else {
        $and .= " AND dod = '".date('Y-m-d')."' GROUP BY orderd.phone";   
    }

    $search = '';
    if(isset($_POST['search']['value']) && $_POST['search']['value'] != '') {
        $search .= " AND (orderd.id REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR orderd.name REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR orderd.phone REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR orderd.city REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR orderd.address REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR orderd.dod REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR orderd.tod REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR orderd.comment REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR admins.name REGEXP '". $_POST['search']['value'] ."')";
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

    $stmt = $con->prepare($query.$ci.$and.$search);
    $stmt->execute();
    $filtered_rows = $stmt->rowCount();

    $stmt = $con->prepare($query.$ci.$and.$search.$order.$limit);
    $stmt->execute();
    $fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $data = [];
    
    foreach ($fetch as $row) {
        $sub_array = [];
        $sub_array[] = '<input type="checkbox" class="checkedListstage" name="checkedList" value="'.$row['ids'].'"/>';
        $sub_array[] = $row['count'] === '1' ? 'Single' : 'Package';
        $sub_array[] = $row['dod'];
        $sub_array[] = $row['tod'];
        $sub_array[] = $row['name'];
        $sub_array[] = $row['phone'];
        $sub_array[] = $row['address'];
        $sub_array[] = $row['city'];
        $sub_array[] = $row['sales'];
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
