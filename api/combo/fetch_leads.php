<?php ob_start();
include('../../main/database.php');
function get_total_leads_records()
{
  ob_start();
  include('../../main/database.php');
  $query = "SELECT * FROM `combo` WHERE status = 'hold' AND deleted_at IS NULL";
  $stmt = $con->prepare($query);
  $stmt->execute();
  return $stmt->rowCount();
}
$columnName = [
  1 => 'c.order_id',
  2 => 'f.fingerprint_value',
  3 => 'c.name',
  4 => 'c.phone',
  5 => 'c.address',
  6 => 'c.created_at'
];
$output = [];
$query = "SELECT c.*, ca.campaign_id as cid, 
                ca.campaign_name as cname, 
                ca.product_name as prname,
                f.fingerprint_value as fingerprint
                FROM combo as c
                LEFT JOIN campaigns as ca ON c.goods_id = ca.campaign_id 
                LEFT JOIN fingerprints as f ON c.user_id = f.fingerprint_name 
                WHERE c.status = 'hold' AND c.deleted_at IS NULL";

$search = '';
if (isset($_POST['search']['value']) && $_POST['search']['value'] != '') {
  $search .= " AND (c.order_id REGEXP '" . $_POST['search']['value'] . "'";
  $search .= " OR c.name REGEXP '" . $_POST['search']['value'] . "'";
  $search .= " OR c.phone REGEXP '" . $_POST['search']['value'] . "'";
  $search .= " OR c.address REGEXP '" . $_POST['search']['value'] . "'";
  $search .= " OR c.order_id REGEXP '" . $_POST['search']['value'] . "'";
  $search .= " OR c.user_id REGEXP '" . $_POST['search']['value'] . "'";
  $search .= " OR f.fingerprint_value REGEXP '" . $_POST['search']['value'] . "'";
  $search .= " OR c.created_at REGEXP '" . $_POST['search']['value'] . "')";
}
$order = '';
if (isset($_POST['order'])) {
  $order .= " ORDER BY " . $columnName[$_POST['order'][0]['column']] . ' ' . $_POST['order'][0]['dir'];
} else {
  $order .= " ORDER BY c.created_at DESC";
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
  $sub_array[] = '<input type="checkbox" class="checkedList" name="checkedList" 
                        data-name="' . $row['name'] . '"
                        data-phone="' . $row['phone'] . '"
                        data-address="' . $row['address'] . '"
                        data-id="' . $row['id'] . '"
                        data-com="' . $row['comment'] . '"
                        data-prn="' . $row['prname'] . '"
                        data-oid="' . $row['order_id'] . '"
                        data-uid="' . $row['user_id'] . '"
                        data-cra="' . $row['created_at'] . '"
                        value="' . $row['id'] . '"
                        />';

  $sub_array[] = $row['order_id'];
  $sub_array[] = $row['fingerprint'] ?? $row['user_id'];
  $sub_array[] = $row['name'];
  $sub_array[] = $row['phone'];
  $sub_array[] = $row['address'];

  $sub_array[] = $row['cid'];
  $sub_array[] = $row['prname'];
  $sub_array[] = $row['cname'];

  $sub_array[] = $row['comment'];
  $sub_array[] = $row['created_at'];
  $sub_array[] = '<button type="button" onclick="deleteOne(' . $row["id"] . ",'combo'," . "'Once you delete our lead is gone'," . "'../delete_lead.php'," . "'Your lead is save!'" . ')" class="btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i></button>
        <button id="edit_lead" class="btn btn-sm btn-outline-success" 
        data-id="' . $row['id'] . '" 
        data-name="' . $row['name'] . '"
        data-phone="' . $row['phone'] . '"
        data-com="' . $row['comment'] . '"
        data-toggle="modal" data-target="#EditLeadModal">Edit
    </button>';
  $data[] = $sub_array;
}

$output = [
  'draw'              => intval($_POST['draw']),
  'recordsTotal'      => get_total_leads_records(),
  'recordsFiltered'   => $filtered_rows,
  'data'              => $data
];

echo json_encode($output);
