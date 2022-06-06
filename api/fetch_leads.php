<?php ob_start();
include('../main/database.php');
function get_total_leads_records()
{
  ob_start();
  include('../main/database.php');
  $query = "SELECT * FROM `terraleads` WHERE test_status != 1 AND deleted_at IS NULL";
  $stmt = $con->prepare($query);
  $stmt->execute();
  return $stmt->rowCount();
}
$columnName = [
  1 => 't.lead_id',
  2 => 't.lead_status',
  3 => 't.name',
  4 => 't.lead_id',
  5 => 't.country',
  6 => 't.phone',
  7 => 't.address',

  8 => 'ca.campaign_id',
  9 => 'ca.campaign_name',
  10 => 'ca.product_name',

  11 => 't.cost',
  12 => 't.cost_delivery',
  13 => 't.landing_cost',
  14 => 't.landing_cost',

  15 => 'f.fingerprint_alue',
  16 => 't.stream_id',
  17 => 't.ip',
  18 => 't.user_agent',

  19 => 't.tz',
  20 => 't.add_date'
];
$query = '';
$output = [];
$query .= "SELECT t.*, ca.campaign_id as cid, 
          ca.campaign_name as cname, 
          ca.product_name as prname,
          f.fingerprint_value as fingerprint
          FROM terraleads as t 
          LEFT JOIN campaigns as ca ON t.campaign_id = ca.campaign_id 
          LEFT JOIN fingerprints as f ON t.web_id = f.fingerprint_name 
          WHERE t.test_status != 1 AND t.deleted_at IS NULL";

$search = '';
if (isset($_POST['search']['value']) && $_POST['search']['value'] != '') {
  $search .= " AND (t.lead_id REGEXP '" . $_POST['search']['value'] . "'";
  $search .= " OR t.name REGEXP '" . $_POST['search']['value'] . "'";
  $search .= " OR t.phone REGEXP '" . $_POST['search']['value'] . "'";
  $search .= " OR t.country REGEXP '" . $_POST['search']['value'] . "'";
  $search .= " OR t.address REGEXP '" . $_POST['search']['value'] . "'";
  $search .= " OR t.lead_status REGEXP '" . $_POST['search']['value'] . "'";
  $search .= " OR t.web_id REGEXP '" . $_POST['search']['value'] . "'";
  $search .= " OR ca.campaign_id REGEXP '" . $_POST['search']['value'] . "'";
  $search .= " OR ca.campaign_name REGEXP '" . $_POST['search']['value'] . "'";
  $search .= " OR ca.product_name REGEXP '" . $_POST['search']['value'] . "'";
  $search .= " OR f.fingerprint_value REGEXP '" . $_POST['search']['value'] . "'";
  $search .= " OR t.add_date REGEXP '" . $_POST['search']['value'] . "')";
}
$order = '';
if (isset($_POST['order'])) {
  $order .= " ORDER BY " . $columnName[$_POST['order'][0]['column']] . ' ' . $_POST['order'][0]['dir'];
} else {
  $order .= " ORDER BY t.lead_id DESC";
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
                        data-country="' . $row['country'] . '"
                        data-address="' . $row['address'] . '"
                        data-prn="' . $row['prname'] . '"
                        data-id="' . $row['id'] . '"
                        data-lid="' . $row['lead_id'] . '"
                        data-wid="' . $row['web_id'] . '"
                        data-add="' . $row['add_date'] . '"
                        value="' . $row['id'] . '"
                        />';

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

  $sub_array[] = $row['fingerprint'] ?? $row['web_id'];
  $sub_array[] = $row['stream_id'];
  $sub_array[] = $row['ip'];
  $sub_array[] = $row['user_agent'];

  $sub_array[] = $row['tz'];
  $sub_array[] = $row['add_date'];
  $sub_array[] = '<button type="button" onclick="deleteOne(' . $row["id"] . ",'terraleads'," . "'Once you delete our lead is gone'," . "'delete_lead.php'," . "'Your lead is save!'" . ')" class="btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i></button>
        <button id="edit_lead" class="btn btn-sm btn-outline-success" 
        data-id="' . $row['id'] . '" 
        data-name="' . $row['name'] . '"
        data-phone="' . $row['phone'] . '"
        data-add="' . $row['address'] . '"
        data-country="' . $row['country'] . '"
        data-toggle="modal" data-target="#EditLeadModal">Edit
    </button>';
  $data[] = $sub_array;
}

$output = [
  'draw'              => intval($_POST['draw']),
  'recordsTotal'      => get_total_leads_records(),
  'recordsFiltered'   => $filtered_rows,
  'data'              => $data,
  'orderby'           => $_POST['order'][0]['column']
];

echo json_encode($output);
