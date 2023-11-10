<?php
ob_start();
session_start();
$getName = $_SESSION['pnorderd'];
include('../main/database.php');
function get_total_orderd_records()
{
    ob_start();
    // session_start();
    $getName = $_SESSION['pnorderd'];
    $where = $getName !== 'all' ? " WHERE pname = '" . $getName . "'" : " WHERE pname IS NOT NULL";
    include('../main/database.php');
    $query = "SELECT orderd.*, databases_connections.id as dbid, databases_connections.network_ads, admins.name as operator FROM orderd LEFT JOIN admins ON orderd.emp_id = admins.id LEFT JOIN databases_connections ON databases_connections.id = orderd.db_id $where";
    $stmt = $con->prepare($query);
    $stmt->execute();
    return $stmt->rowCount();
}

$columnName = [
    1 => 'orderd.id',
    2 => 'orderd.name',
    3 => 'orderd.phone',
    4 => 'orderd.address',
    5 => 'orderd.city',
    6 => 'orderd.prname',
    7 => 'orderd.prprice',
    8 => 'orderd.comment',
    9 => 'admins.name',
    10 => 'orderd.network_ads',
    11 => 'fingerprint_value',
    12 => 'orderd.lead_from',
    13 => 'orderd.wod',
    14 => 'orderd.doo',
    15 => 'orderd.dod',
    16 => 'orderd.added_at',
    17 => 'orderd.created_at',
];
$query = '';
$output = [];
$where = $getName !== 'all' ? "WHERE pname = '" . $getName . "'" : "WHERE pname IS NOT NULL";

$query .= "SELECT orderd.*,
 databases_connections.id as dbid, 
 databases_connections.network_ads, 
 admins.name as operator, f.fingerprint_value as fingerprint FROM orderd 
 LEFT JOIN admins ON orderd.emp_id = admins.id 
 LEFT JOIN databases_connections ON databases_connections.id = orderd.db_id 
 LEFT JOIN fingerprints as f ON orderd.web_id = f.fingerprint_name
 $where";


$emp_id = $_POST['emp_id'];
$city = $_POST['city'];
$shipping = $_POST['shipping'];
$network = $_POST['network'];
$date_first = $_POST['date_first'];
$date_last = $_POST['date_last'];
$emp = '';
if (isset($emp_id) && !empty($emp_id) && $emp_id !== 'None') {
    $emp = " AND orderd.emp_id = '" . $emp_id . "'";
}

if (isset($emp_id) && $emp_id === 'None') {
    $emp = " AND orderd.emp_id IS NULL";
}

$ci = '';
if (isset($city) && !empty($city) && $city !== 'NULL') {
    $ci = " AND orderd.city = '" . $city . "'";
}

$sh = '';
if (isset($shipping) && !empty($shipping) && $shipping !== 'NULL') {
    $sh = " AND orderd.wod = '" . $shipping . "'";
}

$net = '';
if (isset($network) && !empty($network) && $network !== 'NULL') {
    $net = " AND orderd.db_id = '" . $network . "'";
}

if (isset($network) && $network === 'None') {
    $net = " AND orderd.db_id IS NULL";
}

$and = '';
if (isset($date_first) && isset($date_last) &&  $date_first !== '' && $date_last !== '') {
    $and = " AND DATE(orderd.created_at) between '" . $date_first . "' and '" . $date_last . "'";
}

$search = '';
if (isset($_POST['search']['value']) && $_POST['search']['value'] != '') {
    $search .= " AND (orderd.id REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR orderd.name REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR orderd.phone REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR orderd.prname REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR orderd.prprice REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR orderd.prpieces REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR orderd.prcurrency REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR orderd.city REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR orderd.comment REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR orderd.wod REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR orderd.doo REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR orderd.dod REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR orderd.web_id REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR orderd.db_id REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR orderd.lead_from REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR f.fingerprint_value REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR orderd.created_at REGEXP '" . $_POST['search']['value'] . "')";
}
$order = '';
if (isset($_POST['order'])) {
    $order = " ORDER BY " . $columnName[$_POST['order'][0]['column']] . ' ' . $_POST['order'][0]['dir'];
} else {
    $order = " ORDER BY orderd.created_at DESC";
}
$limit = '';
if ($_POST['length'] != -1) {
    $limit = " LIMIT " . $_POST['start'] . ", " . $_POST['length'];
}


$stmt = $con->prepare($query . $emp . $ci . $sh . $and . $search);
$stmt->execute();
$filtered_rows = $stmt->rowCount();

$stmt = $con->prepare($query . $emp . $ci . $sh . $net . $and  . $search . $order . $limit);
$stmt->execute();
$fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
$data = [];

foreach ($fetch as $row) {
    $sub_array = [];
    $sub_array[] = '<input type="checkbox" class="checkedList" name="checkedList" value="' . $row['id'] . '"/>';
    $sub_array[] = $row['id'];
    $sub_array[] = $row['name'];
    $sub_array[] = $row['phone'];
    $sub_array[] = $row['address'];
    $sub_array[] = $row['city'];
    $sub_array[] = '<div>' . $row['prpieces'] . ' <span class="font-weight-bold">' . $row['prname'] . "</span></div><div>";
    $sub_array[] = '<span class="badge badge-danger badge-pill p-2">' . $row['prprice']  . $row['prcurrency'] . '</span>';
    $sub_array[] = $row['comment'];
    $sub_array[] = $row['operator'];
    $sub_array[] = $row['network_ads'];
    $sub_array[] = $row['fingerprint'] ?? $row['web_id'];
    $sub_array[] = $row['lead_from'];
    $sub_array[] = $row['wod'];
    $sub_array[] = $row['doo'];
    $sub_array[] = $row['dod'];
    $sub_array[] = $row['added_at'];
    $sub_array[] = $row['created_at'];
    $sub_array[] = $row['sub'];
    $sub_array[] = '<button id="edit_approved" class="btn btn-sm btn-outline-success" 
                            data-id="' . $ids . '" 
                            data-name="' . $row['name'] . '"
                            data-phone="' . $row['phone'] . '"
                            data-add="' . $row['address'] . '"
                            data-city="' . $row['city'] . '"
                            data-pn="' . $row['pname'] . '"
                            data-prn="' . $row['prname'] . '"
                            data-prpi="' . $row['prpieces'] . '"
                            data-prp="' . $row['prprice'] . '"
                            data-prc="' . $row['prcurrency'] . '"
                            data-wod="' . $row['wod'] . '"
                            data-doo="' . $row['doo'] . '"
                            data-dod="' . $row['dod'] . '"
                            data-com="' . $row['comment'] . '"
                            data-toggle="modal" data-target="#EditOrderdModal">Edit
                        </button>';
    $sub_array[] = '<button id="history" class="btn btn-sm btn-outline-primary" 
                            data-id="' . $ids . '"
                            data-pending="' . $row['pending_id'] . '"
                            data-toggle="modal" data-target="#HistoryModal">
                            <i class="fas fa-eye"></i>
                        </button>';
    $sub_array[] = '<a target="_blank" href="invoice.php?orderId=' . $ids . '&customer=' . $row['name'] . '&pieces=' . $row['prpieces'] . '&prod=' . $row['prname'] . '&price=' . $row['prprice'] . '&address=' . $row['address'] . '&phone=' . $row['phone'] . '" class="btn btn-sm btn-outline-primary" ><i class="fas fa-file-invoice"></i></a>';
    $data[] = $sub_array;
}
$output = [
    'draw'              => intval($_POST['draw']),
    'recordsTotal'      => get_total_orderd_records(),
    'recordsFiltered'   => $filtered_rows,
    'data'              => $data,
];
echo json_encode($output);
