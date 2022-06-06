<?php ob_start();
include('../main/database.php');
function get_total_leads_records()
{
  ob_start();
  include('../main/database.php');
  $query = "SELECT terraleads.*, campaigns.campaign_id as cid, campaigns.campaign_name as cname, campaigns.product_name as prname FROM terraleads LEFT JOIN campaigns ON terraleads.campaign_id = campaigns.campaign_id WHERE terraleads.test_status != 1 AND terraleads.comment = 'duplicate'";
  $stmt = $con->prepare($query);
  $stmt->execute();
  return $stmt->rowCount();
}
$columnName = [
  1 => 'lead_id',
  2 => 'lead_status',
  3 => 'name',
  4 => 'lead_id',
  5 => 'country',
  6 => 'phone',
  7 => 'address',

  8 => 'campaign_id',

  9 => 'cost',
  10 => 'cost_delivery',
  11 => 'landing_cost',
  12 => 'landing_cost',

  13 => 'web_id',
  14 => 'stream_id',
  15 => 'ip',
  16 => 'user_agent',

  17 => 'tz',
  18 => 'add_date'
];
$query = '';
$output = [];
$query .= "SELECT terraleads.*, campaigns.campaign_id as cid, campaigns.campaign_name as cname, campaigns.product_name as prname FROM terraleads LEFT JOIN campaigns ON terraleads.campaign_id = campaigns.campaign_id WHERE terraleads.test_status != 1 AND terraleads.comment = 'duplicate'";

$search = '';
if (isset($_POST['search']['value']) && $_POST['search']['value'] != '') {
  $search .= " AND (terraleads.lead_id REGEXP '" . $_POST['search']['value'] . "'";
  $search .= " OR terraleads.name REGEXP '" . $_POST['search']['value'] . "'";
  $search .= " OR terraleads.phone REGEXP '" . $_POST['search']['value'] . "'";
  $search .= " OR terraleads.country REGEXP '" . $_POST['search']['value'] . "'";
  $search .= " OR terraleads.address REGEXP '" . $_POST['search']['value'] . "'";
  $search .= " OR terraleads.lead_status REGEXP '" . $_POST['search']['value'] . "'";
  $search .= " OR terraleads.add_date REGEXP '" . $_POST['search']['value'] . "')";
}
$order = '';
if (isset($_POST['order'])) {
  $order .= " ORDER BY " . $columnName[$_POST['order'][0]['column']] . ' ' . $_POST['order'][0]['dir'];
} else {
  $order .= " ORDER BY lead_id DESC";
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

  $sub_array[] = $row['lead_id'];
  $sub_array[] = $row['lead_status'];
  $sub_array[] = $row['comment'];
  $sub_array[] = $row['name'];
  $sub_array[] = $row['country'];
  $sub_array[] = $row['phone'];
  $sub_array[] = $row['address'];

  $sub_array[] = $row['cid'];
  $sub_array[] = $row['cname'];
  $sub_array[] = $row['prname'];

  $sub_array[] = $row['cost'];
  $sub_array[] = $row['cost_delivery'];
  $sub_array[] = $row['landing_cost'];
  $sub_array[] = $row['landing_currency'];

  $sub_array[] = $row['web_id'];
  $sub_array[] = $row['stream_id'];
  $sub_array[] = $row['ip'];
  $sub_array[] = $row['user_agent'];

  $sub_array[] = $row['tz'];
  $sub_array[] = $row['add_date'];

  $data[] = $sub_array;
}

$output = [
  'draw'              => intval($_POST['draw']),
  'recordsTotal'      => get_total_leads_records(),
  'recordsFiltered'   => $filtered_rows,
  'data'              => $data
];

echo json_encode($output);
