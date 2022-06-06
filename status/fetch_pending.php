<?php ob_start();
session_start();
$getName = $_SESSION['pn'];
include('../main/database.php');
function get_total_one_new_records()
{
    ob_start();
    session_start();
    $getName = $_SESSION['pn'];
    $where = $getName !== 'all' ? "WHERE p.prname = '" . $getName . "'" : "";
    include('../main/database.php');
    $query = "SELECT 
                p.id as pending_id, 
                p.name as pending_name, 
                p.phone as pending_phone,
                p.prname as pending_prname,
                p.prpieces as pending_prpieces,
                p.prprice as pending_prprice,
                p.prcurrency as pending_prcurrency,
                p.lead_from as pending_lead_from,
                p.status as pending_status,
                p.doo as pending_doo,
                p.web_id as pending_web_id,
                p.added_at as pending_added_at,
                p.created_at as pending_created_at,
            a.name as operator, f.fingerprint_value as fingerprint FROM pending as p 
            LEFT JOIN admins as a ON p.emp_id = a.id 
            LEFT JOIN fingerprints as f ON p.web_id = f.fingerprint_name
            $where";
    $stmt = $con->prepare($query);
    $stmt->execute();
    return $stmt->rowCount();
}
$columnName = [
    1 => 'p.id',
    2 => 'p.name',
    3 => 'p.phone',
    4 => 'p.prname',
    5 => 'p.prpieces',
    6 => 'p.prprices',
    7 => 'f.fingerprint_value',
    8 => 'p.lead_from',
    9 => 'a.name',
    10 => 'p.status',
    11 => 'p.doo',
    12 => 'p.added_at',
    13 => 'p.created_at'
];
$query = '';
$output = [];
$where = $getName !== 'all' ? "WHERE p.prname = '" . $getName . "'" : "WHERE p.prname IS NOT NULL";
$query .= "SELECT 
                    p.id as pending_id, 
                    p.phone as pending_phone,
                    p.name as pending_name, 
                    p.prname as pending_prname,
                    p.prpieces as pending_prpieces,
                    p.prprice as pending_prprice,
                    p.prcurrency as pending_prcurrency,
                    p.lead_from as pending_lead_from,
                    p.status as pending_status,
                    p.doo as pending_doo,
                    p.web_id as pending_web_id,
                    p.added_at as pending_added_at,
                    p.created_at as pending_created_at,
            a.name as operator, f.fingerprint_value as fingerprint FROM pending as p 
            LEFT JOIN admins as a ON p.emp_id = a.id 
            LEFT JOIN fingerprints as f ON p.web_id = f.fingerprint_name
            $where";

$emp_id = $_POST['emp_id'];
$status = $_POST['status'];
$date_first = $_POST['date_first'];
$date_last = $_POST['date_last'];

$emp = '';
if (isset($emp_id) && !empty($emp_id) && $emp_id !== 'NULL') {
    $emp = " AND p.emp_id = '" . $emp_id . "'";
}
if ($emp_id === 'NULL') {
    $emp = " AND p.emp_id IS NULL";
}

$st = '';
if (isset($status) && !empty($status) && $status !== 'NULL') {
    $st = " AND p.status = '" . $status . "'";
}
if ($status === 'NULL') {
    $st = " AND p.status IS NULL";
}

$and = '';
if (isset($date_first) && isset($date_last) &&  $date_first !== '' && $date_last !== '' && ($status === '' || $emp_id === '')) {
    $and = " AND DATE(p.added_at) between '" . $date_first . "' and '" . $date_last . "'";
}

if (isset($date_first) && isset($date_last) &&  $date_first !== '' && $date_last !== '' && ($status !== '' || $emp_id !== '')) {
    $and = " AND DATE(p.created_at) between '" . $date_first . "' and '" . $date_last . "'";
}

$search = '';
if (isset($_POST['search']['value']) && $_POST['search']['value'] != '') {
    $search .= " AND (p.id REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR p.name REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR p.phone REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR p.address REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR p.prname REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR p.prprice REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR p.prpieces REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR p.prcurrency REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR p.status REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR p.lead_from REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR p.web_id REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR f.fingerprint_value REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR p.created_at REGEXP '" . $_POST['search']['value'] . "')";
}
$order = '';
if (isset($_POST['order'])) {
    $order = " ORDER BY " . $columnName[$_POST['order'][0]['column']] . ' ' . $_POST['order'][0]['dir'];
} else {
    $order = " ORDER BY p.added_at DESC";
}
$limit = '';
if ($_POST['length'] != -1) {
    $limit = " LIMIT " . $_POST['start'] . ", " . $_POST['length'];
}

$stmt = $con->prepare($query . $emp . $st . $and . $search);
$stmt->execute();
$filtered_rows = $stmt->rowCount();

$stmt = $con->prepare($query . $emp . $st . $and . $search . $order . $limit);
$stmt->execute();
$fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
$data = [];

foreach ($fetch as $row) {
    $sub_array = [];
    $sub_array[] = '<input type="checkbox" class="checkedList" name="checkedList" value="' . $row['pending_id'] . '"/>';
    $sub_array[] = $row['pending_id'];
    $sub_array[] = $row['pending_name'];
    $sub_array[] = $row['pending_phone'];
    $sub_array[] = $row['pending_prname'];
    $sub_array[] = $row['pending_prpieces'];
    $sub_array[] = '<span class="badge badge-danger badge-pill p-2">' . $row['pending_prprice'] . ' ' . $row['pending_prcurrency'] . '</span>';
    $sub_array[] = $row['fingerprint'] ?? $row['pending_web_id'];
    $sub_array[] = $row['pending_lead_from'];
    $sub_array[] = $row['operator'];
    $sub_array[] = $row['pending_status'];
    $sub_array[] = $row['pending_doo'];
    $sub_array[] = $row['pending_added_at'];
    $sub_array[] = $row['pending_created_at'];
    $sub_array[] = '<button id="history" class="btn btn-sm btn-outline-primary" 
                            data-id="' . $row['pending_id'] . '"
                            data-pending="' . $row['pending_id'] . '"
                            data-toggle="modal" data-target="#HistoryModal">
                            <i class="fas fa-eye"></i>
                        </button>';
    $data[] = $sub_array;
}

$output = [
    'draw'              => intval($_POST['draw']),
    'recordsTotal'      => get_total_one_new_records(),
    'recordsFiltered'   => $filtered_rows,
    'data'              => $data,
    'query'             => $emp . $st . $and
];

echo json_encode($output);
