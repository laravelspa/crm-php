<?php ob_start();
    session_start();
    include('../main/database.php'); 
    function get_total_users_records() {
        ob_start();
        session_start();
        include('../main/database.php');
        $query = "SELECT * FROM sales_applicants WHERE deleted_at IS NULL";
        $stmt = $con->prepare($query);
        $stmt->execute();
        return $stmt->rowCount();
    }
    $columnName = [
        1 => 'id',
        2 => 'name',
        3 => 'phone',
        4 => 'comment',
        5 => 'editor',
        6 => 'created_at',
        7 => 'id'
    ];
    $query = '';
    $output = [];
    $query .= "SELECT * FROM sales_applicants WHERE deleted_at IS NULL";

    $search = '';
    if(isset($_POST['search']['value']) && $_POST['search']['value'] != '') {
        $search .= " AND (id REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR name REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR editor REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR comment REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR created_at REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR phone REGEXP '". $_POST['search']['value'] ."')";
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
        $sub_array[] = $row['phone'];
        $sub_array[] = $row['comment'];
        $sub_array[] = $row['editor'];
        $sub_array[] = $row['created_at'];
        $sub_array[] = '<button type="button" 
                            id="edit_applicant" class="btn btn-sm btn-outline-success" data-toggle="modal" 
                            data-target="#editApplicantModal" 
                            data-id="'.$row["id"].'" 
                            data-name="'.$row["name"].'"
                            data-phone="'.$row["phone"].'"
                            data-com="'.$row["comment"].'">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button type="button" onclick="deleteOne('.$row["id"].",'sales_applicants',"."'Once you delete this applicant is gone',"."'/delete.php',"."'Your user is save!'".')" class="btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i></button>';
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
