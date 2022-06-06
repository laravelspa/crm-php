<?php ob_start();
include('../main/database.php');
function get_total_leads_records()
{
    ob_start();
    include('../main/database.php');
    $query = "SELECT * FROM fingerprints WHERE deleted_at IS NULL";
    $stmt = $con->prepare($query);
    $stmt->execute();
    return $stmt->rowCount();
}
$columnName = [
    1 => 'id',
    2 => 'fingerprint_name',
    3 => 'fingerprint_value',
    4 => 'id',
    5 => 'add_date'
];
$query = '';
$output = [];
$query .= "SELECT * FROM fingerprints WHERE deleted_at IS NULL";

$search = '';
if (isset($_POST['search']['value']) && $_POST['search']['value'] != '') {
    $search .= " AND (id REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR fingerprint_name REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR fingerprint_value REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR note REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR add_date REGEXP '" . $_POST['search']['value'] . "')";
}
$order = '';
if (isset($_POST['order'])) {
    $order .= " ORDER BY " . $columnName[$_POST['order'][0]['column']] . ' ' . $_POST['order'][0]['dir'];
} else {
    $order .= " ORDER BY id DESC";
}
$limit = '';
if ($_POST['length'] != -1) {
    $order .= " LIMIT " . $_POST['start'] . ", " . $_POST['length'];
}
$stmt = $con->prepare($query . $search);
$stmt->execute();
$filtered_rows = $stmt->rowCount();

$stmt = $con->prepare($query . $search . $order . $limit);
$stmt->execute();
$fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
$data = [];

foreach ($fetch as $row) {
    $sub_array = [];
    $sub_array[] = '<input type="checkbox" class="checkedList" name="checkedList" value="' . $row['id'] . '"  />';

    $sub_array[] = $row['id'];
    $sub_array[] = $row['fingerprint_name'];
    $sub_array[] = $row['fingerprint_value'];
    $sub_array[] = $row['note'];
    $sub_array[] = $row['add_date'];
    $sub_array[] = '<button type="button" onclick="deleteOne(' . $row["id"] . ",'fingerprints'," . "'Once you delete fingerprint is gone'," . "'delete_fingerprint.php'," . "'Your fingerprint is safe!'" . ')" class="btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i></button>
        <button id="edit_fingerprint" class="btn btn-sm btn-outline-success" 
                              data-id="' . $row['id'] . '" 
                              data-fname="' . $row['fingerprint_name'] . '"
                              data-fvalue="' . $row['fingerprint_value'] . '"
                              data-note="' . $row['note'] . '"
                              data-toggle="modal" data-target="#editFingerprintModal">
                              <i class="fa fa-edit"></i>
                          </button>';
    $data[] = $sub_array;
}

$output = [
    'draw'              => intval($_POST['draw']),
    'recordsTotal'      => get_total_leads_records(),
    'recordsFiltered'   => $filtered_rows,
    'data'              => $data,
    'query'             => $query . $search . $order . $limit
];

echo json_encode($output);
