<?php 
    ob_start();
    session_start();
    include('../main/database.php');
    function get_total_orderd_records() {
        include('../main/database.php');
        $query = "SELECT * FROM products_inventory.*, inventory.name INNER JOIN inventory ON products_inventory.inventory_id = inventory.id WHERE inventory.name = 'Aramex'";
        $stmt = $con->prepare($query);
        $stmt->execute();
        return $stmt->rowCount();   
    }
    $columnName = [
        0 => 'products_inventory.id',
        1 => 'products_inventory.product_name',
        2 => 'products_inventory.quantity',
        3 => 'products_inventory.created_at',
        4 => 'products_inventory.updated_at',
    ];
    $query = '';
    $output = [];
    
    $query .= "SELECT products_inventory.*, inventory.name FROM products_inventory INNER JOIN inventory ON products_inventory.inventory_id = inventory.id WHERE inventory.name = 'Aramex'";
    
    $search = '';
    if(isset($_POST['search']['value']) && $_POST['search']['value'] != '') {
        $search .= " AND (products_inventory.id REGEXP '". $_POST['search']['value'] ."'";
        $search .= " AND (products_inventory.product_name REGEXP '". $_POST['search']['value'] ."'";
        $search .= " AND (products_inventory.quantity REGEXP '". $_POST['search']['value'] ."'";
        $search .= " AND (products_inventory.created_at REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR products_inventory.updated_at REGEXP '". $_POST['search']['value'] ."')";
    }
    $order = '';
    if(isset($_POST['order'])) {
        $order = " ORDER BY ". $columnName[$_POST['order'][0]['column']].' '. $_POST['order'][0]['dir'];
    } else {
        $order = " ORDER BY products_inventory.id DESC";
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
        $sub_array[] = $row['product_name'];
        $sub_array[] = $row['quantity'];
        $sub_array[] = '<button type="button" id="edit_project" 
                        class="btn btn-sm btn-outline-success" 
                        data-toggle="modal" data-target="#editModal" 
                        data-id="'.$row["id"].'" data-prn="'.$row["product_name"].'" data-qua="'.$row["quantity"].'" data-invid="'.$row["inventory_id"].'"><i class="fa fa-edit fa-sm"></i>
                        </button>
                        <button onclick="deleteProduct('."'".$row["id"]."','/delete.php',"."'#table_aramex', 'products_inventory'".');" 
                        class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-sm fa-trash"></i>
                        </button>
                        <button type="button" id="add_quantity" 
                        class="btn btn-sm btn-outline-warning" data-toggle="modal" data-target="#AddLeadModal" data-id="'.$row["id"].'">
                        <i class="fa fa-plus fa-sm"></i>
                        </button>';
        $sub_array[] = $row['created_at'];
        $sub_array[] = $row['updated_at'];
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
