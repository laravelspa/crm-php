<?php ob_start();
    session_start();
    $table = $_SESSION['dbtable'];
    include('../main/database_generator.php');
    function get_total_leads_records() {
        ob_start();
        session_start();
        $table = $_SESSION['dbtable'];
        include('../main/database_generator.php');
        $query = "SELECT * FROM $table";
        $stmt = $con1->prepare($query);
        $stmt->execute();
        return $stmt->rowCount();
    }
    $columnName = [
        1 => 'id',
        2 => 'name',
        3 => 'phone',
        4 => 'lead_from',
        5 => 'order_at',
        6 => 'id'
    ];
    $query = '';
    $output = [];
    $query .= "SELECT * FROM $table";

    $search = '';
    if(isset($_POST['search']['value']) && $_POST['search']['value'] != '') {
        $search .= " WHERE (id REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR name REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR phone REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR lead_from REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR order_at REGEXP '". $_POST['search']['value'] ."')";
    }
    $order = '';
    if(isset($_POST['order'])) {
        $order .= " ORDER BY ". $columnName[$_POST['order'][0]['column']].' '. $_POST['order'][0]['dir'];
    } else {
        $order .= " ORDER BY id DESC";
    }
    $limit = '';
    if ($_POST['length'] != -1) {
        $order .= " LIMIT ". $_POST['start'] . ", " . $_POST['length'];
    }
    $stmt = $con1->prepare($query.$search);
    $stmt->execute();
    $filtered_rows = $stmt->rowCount();
    
    $stmt = $con1->prepare($query.$search.$order.$limit);
    $stmt->execute();
    $fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $data = [];

    foreach ($fetch as $row) {
        $sub_array = [];
        $sub_array[] = '<input type="checkbox" class="checkedList" name="checkedList" value="'.$row['id'].'"  />'; 
        $sub_array[] = $row['id'];
        $sub_array[] = '<a href="#" id="edit_lead" data-id="'.$row['id'].'" data-name="'.$row['name'].'" data-phone="'.$row['phone'].'">'.$row['name'].'</a>';
        $sub_array[] = $row['phone'];
        $sub_array[] = $row['lead_from'];
        $sub_array[] = $row['order_at'];
        $sub_array[] = '<button type="button" onclick="deleteOne('.$row["id"].",'sales',"."'Once you delete our lead is gone',"."'delete_lead.php',"."'Your lead is save!'".')" class="btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i></button>';
        $data[] = $sub_array;
    }

    $output = [
        'draw'              => intval($_POST['draw']),
        'recordsTotal'      => get_total_leads_records(),
        'recordsFiltered'   => $filtered_rows,
        'data'              => $data
    ];

    echo json_encode($output);
?>
