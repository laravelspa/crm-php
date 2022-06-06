<?php ob_start();
include('../../main/database.php');
function get_total_leads_records()
{
  ob_start();
  include('../../main/database.php');
  $query = "SELECT * FROM `combo` WHERE status = 'trash' AND extra_state = 'duplicate' AND deleted_at IS NULL";
  $stmt = $con->prepare($query);
  $stmt->execute();
  return $stmt->rowCount();
}
$columnName = [
  1 => 'order_id',
  2 => 'user_id',
  3 => 'name',
  4 => 'phone',
  5 => 'address',
  6 => 'created_at'
];
$query = '';
$output = [];
$query .= "SELECT combo.*, campaigns.campaign_id as cid, campaigns.campaign_name as cname, campaigns.product_name as prname FROM combo LEFT JOIN campaigns ON combo.goods_id = campaigns.campaign_id WHERE combo.extra_state = 'duplicate' AND combo.deleted_at IS NULL";

$search = '';
if (isset($_POST['search']['value']) && $_POST['search']['value'] != '') {
  $search .= " AND (order_id REGEXP '" . $_POST['search']['value'] . "'";
  $search .= " OR name REGEXP '" . $_POST['search']['value'] . "'";
  $search .= " OR phone REGEXP '" . $_POST['search']['value'] . "'";
  $search .= " OR address REGEXP '" . $_POST['search']['value'] . "'";
  $search .= " OR order_id REGEXP '" . $_POST['search']['value'] . "'";
  $search .= " OR user_id REGEXP '" . $_POST['search']['value'] . "'";
  $search .= " OR created_at REGEXP '" . $_POST['search']['value'] . "')";
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

  $sub_array[] = $row['order_id'];
  $sub_array[] = $row['user_id'];
  $sub_array[] = $row['name'];
  $sub_array[] = $row['phone'];
  $sub_array[] = $row['address'];
  
  $sub_array[] = $row['cid'];
  $sub_array[] = $row['prname'];
  $sub_array[] = $row['cname'];

  $sub_array[] = $row['comment'];
  $sub_array[] = $row['created_at'];

  $data[] = $sub_array;
}

$output = [
  'draw'              => intval($_POST['draw']),
  'recordsTotal'      => get_total_leads_records(),
  'recordsFiltered'   => $filtered_rows,
  'data'              => $data
];

echo json_encode($output);
