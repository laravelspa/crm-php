<?php ob_start();
session_start();
$sessionID = $_SESSION['id'];
$is_admin = $_SESSION['is_admin'];

include('../main/database.php');
function get_total_users_records()
{
    ob_start();
    include('../main/database.php');
    $query = "SELECT certificates.*, users.id as user_id, users.name as created_by FROM certificates LEFT JOIN users ON certificates.user_id = users.id WHERE certificates.deleted_at IS NULL";
    $stmt = $con->prepare($query);
    $stmt->execute();
    return $stmt->rowCount();
}
$columnName = [
    1 => 'certificates.id',
    2 => 'certificates.date',
    3 => 'certificates.facility_name',
    4 => 'certificates.mobile',
    5 => 'certificates.commercial_register',
    6 => 'certificates.created_at',
    7 => 'created_by',
    8 => 'certificates.id',
    9 => 'certificates.id'
];
$query = '';
$output = [];
$query .= "SELECT certificates.*, users.id as user_id, users.name as created_by FROM certificates LEFT JOIN users ON certificates.user_id = users.id WHERE certificates.deleted_at IS NULL";

$search = '';
if (isset($_POST['search']['value']) && $_POST['search']['value'] != '') {
    $search .= " AND (certificates.serial_number REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR certificates.facility_name REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR certificates.mobile REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR certificates.commercial_register REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR certificates.date REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR certificates.created_at REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR users.name REGEXP '" . $_POST['search']['value'] . "')";
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
    $sub_array[] = $row['serial_number'];
    $sub_array[] = $row['date'];
    $sub_array[] = $row['facility_name'];
    $sub_array[] = $row['mobile'];
    $sub_array[] = $row['commercial_register'];
    $sub_array[] = date_format(new DateTime($row['created_at']), 'Y-m-d');
    $sub_array[] = $row['created_by'];

    if ($is_admin && !$sessionID) {
        $sub_array[] = '<button type="button" id="edit_certificate" class="btn btn-sm btn-success" data-toggle="modal" data-target="#editCertificateModal" data-id="' . $row["id"] . '" data-serial_number="' . $row["serial_number"] . '" data-date="' . $row["date"] . '" data-facility_name="' . $row["facility_name"] . '" data-facility_activity="' . $row["facility_activity"] . '" data-facility_address="' . $row["facility_address"] . '" data-mobile="' . $row["mobile"] . '" data-commercial_register="' . $row["commercial_register"] . '" data-no_civil_registry="' . $row["no_civil_registry"] . '" data-internal_cameras="' . $row["internal_cameras"] . '" data-external_cameras="' . $row["external_cameras"] . '" data-recording_device="' . $row["recording_device"] . '" data-recording_duration="' . $row["recording_duration"] . '" data-storage_disk="' . $row["storage_disk"] . '" data-display="' . $row["display"] . '" data-other_specifications="' . $row["other_specifications"] . '"><i class="fa fa-edit"></i></button>';
        $sub_array[] = '<button type="button" onclick="deleteOne(' . $row["id"] . ",'users'," . "'سيتم إخفاء هذه الشهادة عند تأكيد الحذف'," . "'/delete.php'," . "'تم إلغاء عملية الحذف!'" . ')" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>';
    } else {
        $sub_array[] = '<button type="button" id="edit_certificate" class="btn btn-sm btn-success" data-toggle="modal" data-target="#editCertificateModal" data-id="' . $row["id"] . '" data-serial_number="' . $row["serial_number"] . '" data-date="' . $row["date"] . '" data-facility_name="' . $row["facility_name"] . '" data-facility_activity="' . $row["facility_activity"] . '" data-facility_address="' . $row["facility_address"] . '" data-mobile="' . $row["mobile"] . '"><i class="fa fa-edit"></i></button>';
        $sub_array[] = '';
    }
    $sub_array[] = '<button type="button" id="print_certificate" class="btn btn-sm btn-warning text-black" data-toggle="modal" data-target="#printCertificateModal" data-serial_number="' . $row["serial_number"] . '" data-date="' . $row["date"] . '" data-facility_name="' . $row["facility_name"] . '" data-facility_activity="' . $row["facility_activity"] . '" data-facility_address="' . $row["facility_address"] . '" data-mobile="' . $row["mobile"] . '" data-commercial_register="' . $row["commercial_register"] . '" data-no_civil_registry="' . $row["no_civil_registry"] . '" data-internal_cameras="' . $row["internal_cameras"] . '" data-external_cameras="' . $row["external_cameras"] . '" data-recording_device="' . $row["recording_device"] . '" data-recording_duration="' . $row["recording_duration"] . '" data-storage_disk="' . $row["storage_disk"] . '" data-display="' . $row["display"] . '" data-other_specifications="' . $row["other_specifications"] . '"><i class="fa fa-print"></i></button>';
    $data[] = $sub_array;
}

$output = [
    'draw'              => intval($_POST['draw']),
    'recordsTotal'      => get_total_users_records(),
    'recordsFiltered'   => $filtered_rows,
    'data'              => $data
];

echo json_encode($output);
