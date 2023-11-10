<?php ob_start();
    session_start();
    $nameInId = $_SESSION['id'];
    include('../main/database.php'); 
    function get_total_users_records() {
        ob_start();
        // session_start();
        $nameInId = $_SESSION['id'];
        include('../main/database.php');
        $query = "SELECT admin.*,parent.name as superparent FROM admins admin LEFT JOIN admins parent ON parent.id = admin.supervisor WHERE admin.id != '".$nameInId."'";
        $stmt = $con->prepare($query);
        $stmt->execute();
        return $stmt->rowCount();
    }
    $columnName = [
        1 => 'id',
        2 => 'name',
        3 => 'permission',
        4 => 'wall',
        5 => 'supervisor',
        6 => 'projects',
        7 => 'location',
        8 => 'id',
        9 => 'id'
    ];
    $query = '';
    $output = [];
    $query .= "SELECT admin.*,parent.name as superparent FROM admins admin LEFT JOIN admins parent ON parent.id = admin.supervisor  WHERE admin.id != '".$nameInId."'";

    $search = '';
    if(isset($_POST['search']['value']) && $_POST['search']['value'] != '') {
        $search .= " AND (admin.id REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR admin.name REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR admin.projects REGEXP '". $_POST['search']['value'] ."')";
    }
    $order = '';
    if(isset($_POST['order'])) {
        $order .= " ORDER BY ". $columnName[$_POST['order'][0]['column']].' '. $_POST['order'][0]['dir'];
    } else {
        $order .= " ORDER BY admin.id DESC";
    }
    $limit = '';
    if ($_POST['length'] != -1) {
        $order .= " LIMIT ". $_POST['start'] . ", " . $_POST['length'];
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
        $sub_array[] = '<input type="checkbox" class="checkedList" name="checkedList" value="'.$row['id'].'"  />'; 
        $sub_array[] = $row['id'];
        $sub_array[] = $row['name'];
        if($row['permission'] == 0) {
            $sub_array[] = 'Admin';
        } else if($row['permission'] == 1) {
            $sub_array[] = 'Supervisor - Calling';
        } else if($row['permission'] == 2) {
            $sub_array[] = 'Sales';
        } else if($row['permission'] == 3) {
            $sub_array[] = 'Supervisor - Delivery';
        } else if($row['permission'] == 4) {
            $sub_array[] = 'Supervisor assistant';
        } else if($row['permission'] == 5) {
            $sub_array[] = 'Delivery Man';
        } else if($row['permission'] == 7) {
            $sub_array[] = 'Status Watcher';
        } else {
            $sub_array[] = 'Deleivery Call';
        }

        if($row['wall'] === '0') {
            $sub_array[] = '<i class="far fa-times-circle text-danger"></i>';
        } else {
            $sub_array[] = '<i class="far fa-check-circle text-success"></i>';
        };

        $sub_array[] = $row['superparent'];
        $sub_array[] = $row['projects'];
        if($row['location'] === '0') {
            $sub_array[] = 'Cairo';
        } else if($row['location'] === '1') {
            $sub_array[] = 'Alexandria';
        } else {
            $sub_array[] = '';
        }
        $sub_array[] = '<button type="button" id="edit_admin" class="btn btn-sm btn-outline-success" data-toggle="modal" data-target="#editAdminModal" data-id="'.$row["id"].'" data-name="'.$row["name"].'" data-pass="'.$row["password"].'" data-permission="'.$row["permission"].'" data-wall="'. $row["wall"].'" data-projects="'.$row["projects"].'" data-sup="'.$row["supervisor"].'" data-location="'.$row["location"].'"><i class="fa fa-edit"></i></button>';
        $sub_array[] = '<button type="button" onclick="deleteOne('.$row["id"].",'admins',"."'Once you delete this user all orders became null',"."'/delete.php',"."'Your user is save!'".')" class="btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i></button>';
        $data[] = $sub_array;
    }

    $output = [
        'draw'              => intval($_POST['draw']),
        'recordsTotal'      => get_total_users_records(),
        'recordsFiltered'   => $filtered_rows,
        'data'              => $data
    ];

    echo json_encode($output);
?>
