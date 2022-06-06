<?php ob_start();
include('../../main/database.php');
function get_total_leads_records()
{
    ob_start();
    include('../../main/database.php');
    $query = "SELECT * FROM campaigns WHERE deleted_at IS NULL";
    $stmt = $con->prepare($query);
    $stmt->execute();
    return $stmt->rowCount();
}
$columnName = [
    1 => 'campaign_id',
    2 => 'campaign_name',
    3 => 'product_name',
    4 => 'campaign_id',
    5 => 'add_date'
];
$query = '';
$output = [];
$query .= "SELECT * FROM campaigns WHERE deleted_at IS NULL";

$search = '';
if (isset($_POST['search']['value']) && $_POST['search']['value'] != '') {
    $search .= " AND (campaign_id REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR campaign_name REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR note REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR add_date REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR product_name REGEXP '" . $_POST['search']['value'] . "')";
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

$companies = [
    '1' => 'Api 1',
    '2' => 'Api 2',
];

foreach ($fetch as $row) {
    $sub_array = [];
    $sub_array[] = '<input type="checkbox" class="checkedList" name="checkedList" value="' . $row['id'] . '"  />';

    $sub_array[] = $row['campaign_id'];
    $sub_array[] = $row['campaign_name'];
    $sub_array[] = $row['product_name'];
    $sub_array[] = $row['note'];
    $sub_array[] = $companies[$row['status']] ?? NULL;
    $sub_array[] = $row['add_date'];


    $sub_array[] = '<button type="button" onclick="deleteOne(' . $row["id"] . ",'campaigns'," . "'Once you delete campaign is gone'," . "'delete_campaign.php'," . "'Your campaign is safe!'" . ')" class="btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i></button>
        <button id="edit_campaign" class="btn btn-sm btn-outline-success" 
                              data-id="' . $row['id'] . '" 
                              data-cid="' . $row['campaign_id'] . '" 
                              data-cname="' . $row['campaign_name'] . '"
                              data-prname="' . $row['product_name'] . '"
                              data-status="' . $row['status'] . '"
                              data-note="' . $row['note'] . '"
                              data-toggle="modal" data-target="#editCampaignModal">
                              <i class="fa fa-edit"></i>
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
