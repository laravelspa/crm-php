<?php ob_start();
session_start();

$is_admin = $_SESSION['is_admin'];
$sessionID = $_SESSION['id'];

include('../main/database.php');

function get_total_users_records()
{
    ob_start();
    include('../main/database.php');
    $query = "SELECT * FROM users WHERE deleted_at IS NULL";
    $stmt = $con->prepare($query);
    $stmt->execute();
    return $stmt->rowCount();
}
$columnName = [
    1 => 'id',
    2 => 'name',
    3 => 'is_admin',
    4 => 'id',
    5 => 'id'
];
$query = '';
$output = [];
$query .= "SELECT * FROM users WHERE deleted_at IS NULL";

$search = '';
if (isset($_POST['search']['value']) && $_POST['search']['value'] != '') {
    $search .= " AND (id REGEXP '" . $_POST['search']['value'] . "'";
    $search .= " OR name REGEXP '" . $_POST['search']['value'] . "')";
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
    $sub_array[] = $row['id'] !== 0 ? '<input type="checkbox" class="checkedList" name="checkedList" value="' . $row['id'] . '"  />' : '';
    $sub_array[] = $row['id'];
    $sub_array[] = $row['name'];
    if ($row['is_admin'] === 0) {
        $sub_array[] = '<i class="far fa-times-circle text-danger"></i>';
    } else {
        $sub_array[] = '<i class="far fa-check-circle text-success"></i>';
    };
    if ($is_admin) {
        $sub_array[] = '<button type="button" id="edit_user" class="btn btn-sm btn-success" data-toggle="modal" data-target="#editUserModal" data-id="' . $row["id"] . '" data-name="' . $row["name"] . '" data-pass="' . $row["password"] . '" data-admin="' . $row["is_admin"] . '"><i class="fa fa-edit"></i></button>';
        $sub_array[] = $row['id'] !== 0 ? '<button type="button" onclick="deleteOne(' . $row["id"] . ",'users'," . "'سيتم إخفاء المستخدم بعد تأكيد الحذف'," . "'/delete.php'," . "'تم إلغاء عملية الحذف!'" . ')" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>': '';
    } else {
        $sub_array[] = '<button type="button" id="edit_user" class="btn btn-sm btn-success" data-toggle="modal" data-target="#editUserModal" data-id="' . $row["id"] . '" data-name="' . $row["name"] . '" data-pass="' . $row["password"] . '" data-admin="' . $row["is_admin"] . '"><i class="fa fa-edit"></i></button>';
        $sub_array[] = '';
    }
    $data[] = $sub_array;
}

$output = [
    'draw'              => intval($_POST['draw']),
    'recordsTotal'      => get_total_users_records(),
    'recordsFiltered'   => $filtered_rows,
    'data'              => $data
];

echo json_encode($output);
