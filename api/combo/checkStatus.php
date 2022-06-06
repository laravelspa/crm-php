<?php

$apiKey = "6a56S8d3ec793G424faH1a8a1cVcfdGG38a386";
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/x-www-form-urlencoded; charset=UTF-8");
// $data = json_decode(file_get_contents('php://input'), true);
include "../../main/database.php";

$data = $_GET;
$ids = explode(',', $data['ids']);

$map_ids = array_map(function ($key) {
  return "'$key'";
}, $ids);

$order_ids = implode(',', $map_ids);

if (isset($_GET['ids'])) {
  if ($_GET['ids'] == "") {
    header("HTTP/1.1 400 Bad Request");
    echo json_encode([
      'status' => 'error',
      'error' => 'please check your request, ids is empty'
    ]);
  } else {
    $stmt = $con->prepare("SELECT order_id, comment, status, extra_state FROM combo WHERE order_id IN($order_ids)");
    $stmt->execute();
    $find = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($find) {
      echo json_encode($find);
    } else {
      header("HTTP/1.1 400 Bad Request");
      echo json_encode([
        'status' => 'error',
        'error' => 'please check your request these ids are not found in our database'
      ]);   
    }
  }
} else {
  header("HTTP/1.1 400 Bad Request");
  echo json_encode([
    'status' => 'error',
    'error' => 'please check your request, ids not included'
  ]);
}
