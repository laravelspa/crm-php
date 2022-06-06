<?php
    include('../main/database.php'); 
        function get_count($dbname,$dbuser,$dbpassword,$table) {
            $dsn2 = "mysql:host=localhost;dbname=$dbname";
            try {
                $con2 = new PDO($dsn2, $dbuser, $dbpassword);
                $stmt = $con2->prepare("SELECT id FROM $table");
                $stmt->execute();
                return $stmt->rowCount();
            }
            catch(PDOException $e) {
                var_dump($e->getMessage());
                // header('location: error.php');
            }
        }
    function get_total_dbs_records() {
        include('../main/database.php');
        $query = "SELECT * FROM databases_connections";
        $stmt = $con->prepare($query);
        $stmt->execute();
        return $stmt->rowCount();
    }
    $columnName = [
        1 => 'db_name',
        2 => 'id',
        3 => 'db_table',
        4 => 'network_ads',
        5 => 'landing_url',
        6 => 'db_user',
        7 => 'prname',
        8 => 'prprice',
        9 => 'comment',
        10 => 'id',
        11 => 'id',
        12 => 'id'
    ];
    $query = '';
    $output = [];
    $query .= "SELECT * FROM databases_connections";

    $search = '';
    if(isset($_POST['search']['value']) && $_POST['search']['value'] != '') {
        $search .= " AND (id REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR db_name REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR network_ads REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR landing_url REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR prname REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR prprice REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR prcurrency REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR db_user REGEXP '". $_POST['search']['value'] ."'";
        $search .= " OR comment REGEXP '". $_POST['search']['value'] ."')";
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
        $sub_array[] = '<input type="checkbox" class="checkedList" name="checkedList" value="'.$row['id'].'"  />'; 
        $sub_array[] = '<a href="leads.php?dbid='.$row['id'].'&dbname='.$row['db_name'].'&dbtable='.$row['db_table'].'&dbuser='.$row['db_user'].'&prn='.$row['prname'].'&prp='.$row['prprice'].'&prc='.$row['prcurrency'].'">'.$row['db_name'].'</a>';
        $sub_array[] = '<span class="badge badge-success p-2">'.get_count($row['db_name'],$row['db_user'],$row['db_password'],$row['db_table']) .' Leads</span>';
        $sub_array[] = $row['db_table'];
        $sub_array[] = $row['network_ads'];
        $sub_array[] = '<a href="'.$row['landing_url'].'" target="_blank">View</a>';
        $sub_array[] = $row['db_user'];
        $sub_array[] = $row['prname'];
        $sub_array[] = '<span class="badge badge-pill badge-danger p-1">'.$row['prprice'] . ' ' . $row['prcurrency'].'</span>';
        $sub_array[] = $row['comment'];
        $sub_array[] = '<button type="button" id="edit_db" class="btn btn-sm btn-outline-success" data-toggle="modal" data-target="#editDatabaseModal" data-id="'.$row["id"].'" data-dbname="'.$row["db_name"].'" data-dbtable="'.$row["db_table"].'" data-dbuser="'.$row["db_user"].'" data-dbpass="'. $row["db_password"].'" data-nads="'. $row["network_ads"].'" data-lur="'. $row["landing_url"].'" data-prn="'. $row["prname"].'" data-prp="'. $row["prprice"].'" data-prc="'. $row["prcurrency"].'" data-com="'. $row["comment"].'"><i class="fa fa-edit"></i></button>';
        $sub_array[] = '<button type="button" onclick="deleteOne('.$row["id"].",'databases_connections',"."'delete this DB connection without delete this DB',"."'/delete.php',"."'Your connection is save!'".')" class="btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i></button>';
        $sub_array[] = '<a href="leads.php?dbid='.$row['id'].'&dbname='.$row['db_name'].'&dbtable=sales_delete&dbuser='.$row['db_user'].'&dbpassword='.$row['db_password'].'&prn='.$row['prname'].'&prp='.$row['prprice'].'&prc='.$row['prcurrency'].'">Temp</a>';
        $data[] = $sub_array;
    }

    $output = [
        'draw'              => intval($_POST['draw']),
        'recordsTotal'      => get_total_dbs_records(),
        'recordsFiltered'   => $filtered_rows,
        'data'              => $data,
        'stmt'              => $stmt,
    ];

    echo json_encode($output);
?>
