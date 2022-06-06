<?php ob_start();
    session_start();
    include('../main/database.php');
    function get_total_projects_records() {
        include('../main/database.php');
        $query = "SELECT pending_dublicate.pname, pending_dublicate.prname, pending_dublicate.prprice, pending_dublicate.prcurrency, pending_dublicate.created_by, count(*) as count, admins.name as operator FROM pending_dublicate LEFT JOIN admins ON pending_dublicate.created_by = admins.id GROUP BY pname";
        $stmt = $con->prepare($query);
        $stmt->execute();
        return $stmt->rowCount();
    }
    $query = '';
    $output = [];
    $query .= "SELECT pending_dublicate.pname, pending_dublicate.prname, pending_dublicate.prprice, pending_dublicate.prcurrency, pending_dublicate.created_by, count(*) as count, admins.name as operator FROM pending_dublicate LEFT JOIN admins ON pending_dublicate.created_by = admins.id GROUP BY pname";
    
    $search = '';
    if(isset($_POST['search']['value']) && $_POST['search']['value'] != '') {
        $search .= " AND (id REGEXP '". $_POST['search']['value'] ."'";
        $search .= " AND pname REGEXP '". $_POST['search']['value'] ."'";
        $search .= " AND created_by REGEXP '". $_POST['search']['value'] ."')";
    }
    $order = '';
    if(isset($_POST['order'])) {
        $order = " ORDER BY pname".' '. $_POST['order'][0]['dir'];
    } else {
        $order = " ORDER BY created_by DESC";
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
        $sub_array[] = '<a href="/products/dublicate.php?project='.$row['pname'].'">'. $row['pname'].'</a>';

        $sub_array[] = '<span class="badge badge-pill badge-success p-1">'.$row['count'].' Leads</span>';
        $sub_array[] = $row['operator'];
        $sub_array[] = '<button onclick="deleteProject('."'".$row["pname"]."','/delete.php',"."'#table_dublicate', 'pending_dublicate'".');" class="btn btn-sm btn-outline-danger"><i class="fas fa-sm fa-trash"></i></button>';
        $data[] = $sub_array;
    }

    $output = [
        'draw'              => intval($_POST['draw']),
        'recordsTotal'      => $filtered_rows,
        'recordsFiltered'   => get_total_projects_records(),
        'data'              => $data
    ];

    echo json_encode($output);
?>
