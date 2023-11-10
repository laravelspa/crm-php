<?php
ob_start();
session_start();
$getName = $_SESSION['pncancel'];
include('../main/database.php');
function get_total_cancel_records()
{
    ob_start();
    // session_start();
    $getName = $_SESSION['pncancel'];
    $where = $getName !== 'all' ? " WHERE pname = '" . $getName . "'" : " WHERE pname IS NOT NULL";
    include('../main/database.php');
    $query = "SELECT canceld.*, admins.name as operator FROM canceld INNER JOIN admins ON canceld.emp_id = admins.id $where";
    $stmt = $con->prepare($query);
    $stmt->execute();
    return $stmt->rowCount();
}

$columnName = [
    1 => 'canceld.id',
    2 => 'canceld.name',
    3 => 'canceld.phone',
    4 => 'canceld.prname',
    5 => 'canceld.prpieces',
    6 => 'canceld.prprice',
    7 => 'fingerprint_value',
    8 => 'canceld.lead_from',
    9 => 'admins.name',
    10 => 'canceld.status',
    11 => 'canceld.added_at',
    12 => 'canceld.canceld_at'
];
$query = '';
$output = [];
$where = $getName !== 'all' ? "WHERE pname = '" . $getName . "'" : "WHERE pname IS NOT NULL";

$query .= "SELECT canceld.*, admins.name as operator, f.fingerprint_value as fingerprint FROM canceld
            INNER JOIN admins ON canceld.emp_id = admins.id 
            LEFT JOIN fingerprints as f ON canceld.web_id = f.fingerprint_name 
            $where";

$emp_id = $_POST['emp_id'];
$status = $_POST['status'];
$date_first = $_POST['date_first'];
$date_last = $_POST['date_last'];

$emp = '';
if (isset($emp_id) && !empty($emp_id) && $emp_id !== 'NULL') {
    $emp = " AND emp_id = '" . $emp_id . "'";
}

$st = '';
if (isset($status) && !empty($status) && $status !== 'NULL' && $status !== 'other') {
    $st = " AND status = '" . $status . "'";
}

if (isset($status) && !empty($status) && $status !== 'NULL' && $status === 'other') {
    $st = ' AND (status != "changed his mind" AND status != "did not order" AND status != "aggressive" AND status != "expensive" AND status != "joke" AND status != "wrong number" AND status != "spam")';
}

$and = '';
if (isset($date_first) && isset($date_last) &&  $date_first !== '' && $date_last !== '') {
    $and = " AND DATE(canceld.canceld_at) between '" . $date_first . "' and '" . $date_last . "'";
}

$search = '';
if (isset($_POST['search']['value']) && $_POST['search']['value'] != '') {
    $search .= " AND (canceld.id REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR canceld.name REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR canceld.phone REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR canceld.prname REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR canceld.prprice REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR canceld.prpieces REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR canceld.prcurrency REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR canceld.status REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR canceld.web_id REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR f.fingerprint_value REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR admins.name REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR canceld.lead_from REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR canceld.canceld_at REGEXP '" . $_POST['search']['value'] . "')";
}
$order = '';
if (isset($_POST['order'])) {
    $order = " ORDER BY " . $columnName[$_POST['order'][0]['column']] . ' ' . $_POST['order'][0]['dir'];
} else {
    $order = " ORDER BY canceld.canceld_at DESC";
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
    $sub_array[] = '<input type="checkbox" class="checkedList" name="checkedList" value="' . $row['id'] . '"/>';
    $sub_array[] = $row['id'];
    $sub_array[] = $row['name'];
    $sub_array[] = $row['phone'];
    $sub_array[] = $row['prname'];
    $sub_array[] = $row['prpieces'];
    $sub_array[] = '<span class="badge badge-danger badge-pill p-2">' . $row['prprice'] . ' ' . $row['prcurrency'] . '</span>';
    $sub_array[] = $row['fingerprint'] ?? $row['web_id'];
    $sub_array[] = $row['lead_from'];
    $sub_array[] = $row['operator'];
    $sub_array[] = $row['status'];
    $sub_array[] = $row['added_at'];
    $sub_array[] = $row['canceld_at'];
    $sub_array[] = '<button id="history" class="btn btn-sm btn-outline-primary" 
                            data-id="' . $row['id'] . '"
                            data-pending="' . $row['pending_id'] . '"
                            data-toggle="modal" data-target="#HistoryModal">
                            <i class="fas fa-eye"></i>
                        </button>';
    $data[] = $sub_array;
}

$output = [
    'draw'              => intval($_POST['draw']),
    'recordsTotal'      => get_total_cancel_records(),
    'recordsFiltered'   => $filtered_rows,
    'data'              => $data
];

echo json_encode($output);
