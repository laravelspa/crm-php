<?php

$apiKey = "6a56S8d3ec793G424faH1a8a1cVcfdGG38a386";
include '../../main/database.php';

// required headers
// header("Access-Control-Allow-Origin: *");
// header("Content-Type: application/x-www-form-urlencoded; charset=UTF-8");

// $data = json_decode(file_get_contents('php://input'), true);
$data = $_POST;
// Recieve Data And Insert Into DB 
if (isset($data['api_key'])) {
  if ($data['api_key'] === $apiKey) {
    if (isset($data['order_id'], $data['user_id'], $data['goods_id'], $data['name'], $data['phone'])) {
      $order_id               = $data['order_id'];
      $goods_id               = $data['goods_id'];
      $user_id                = $data['user_id'];
      $name                   = $data['name'];
      $phone                  = $data['phone'];
      $address                = $data['address'] ?? 'Null';
      $apiKey                 = $data['api_key'];
      $status                 = 'hold';
      $extra_state            = 'new';

      $selectOrder = $con->prepare("SELECT count(*) as count FROM combo where order_id = :order_id");
      $selectOrder->bindParam(':order_id', $order_id, PDO::PARAM_INT);
      $selectOrder->execute();
      $find = $selectOrder->fetch(PDO::FETCH_ASSOC);

      if ($find['count'] !== '0') {
        header("HTTP/1.1 400 Bad Request");
        echo json_encode([
          'order_id' => $order_id,
          'status' => 'error',
          'ext_id' => '',
          'error' => 'duplicate order id'
        ]);
      } else {
        $like_lead_phone = "%" . $phone;
        $selectDub = $con->prepare("SELECT count(*) as count FROM combo where phone LIKE :phone AND user_id = :user_id AND goods_id = :goods_id");
        $selectDub->bindParam('goods_id', $goods_id, PDO::PARAM_STR);
        $selectDub->bindParam('user_id', $user_id, PDO::PARAM_STR);
        $selectDub->bindParam('phone', $like_lead_phone, PDO::PARAM_STR);

        $selectDub->execute();
        $findDub = $selectDub->fetch(PDO::FETCH_ASSOC);

        if ($findDub['count'] !== '0') {
          $status = "trash";
          $extra_state = "duplicate";
        }
        $insertLead = $con->prepare("INSERT INTO combo(order_id , goods_id, user_id, name , phone, address , status, extra_state, comment, created_at)
                                    VALUES(:oid, :gid, :uid, :n, :ph, :add, :st, :es, NULL, NOW())");

        $insertLead->bindParam('oid', $order_id, PDO::PARAM_INT);
        $insertLead->bindParam('gid', $goods_id, PDO::PARAM_STR);
        $insertLead->bindParam('uid', $user_id, PDO::PARAM_STR);
        $insertLead->bindParam('n', $name, PDO::PARAM_STR);
        $insertLead->bindParam('ph', $phone, PDO::PARAM_STR);
        $insertLead->bindParam('add', $address, PDO::PARAM_STR);
        $insertLead->bindParam('st', $status, PDO::PARAM_STR);
        $insertLead->bindParam('es', $extra_state, PDO::PARAM_STR);

        if ($insertLead->execute()) {
          $external_id = $con->lastInsertId();
          header("HTTP/1.1 200 OK");
          echo json_encode([
            'order_id' => $order_id,
            'status' => 'ok',
            'ext_id' => $external_id,
            'error' => ''
          ]);
        } else {
          header("HTTP/1.1 400 Bad Request");
          echo json_encode([
            'order_id' => $order_id,
            'status' => 'error',
            'ext_id' => '',
            'error' => 'unknown error - please check your request or contact advertiser for more info'
          ]);
        }
      }
    } else {
      header("HTTP/1.1 400 Bad Request");
      echo json_encode([
        'order_id' => $_POST['order_id'],
        'status' => 'error',
        'ext_id' => '',
        'error' => 'unknown error - please check your request or contact advertiser for more info'
      ]);
    }
  } else {
    header("HTTP/1.1 400 Bad Request");
    echo json_encode([
      'order_id' => $_POST['order_id'],
      'status' => 'error',
      'ext_id' => '',
      'error' => 'wrong api key'
    ]);
  }
} else {
  header("HTTP/1.1 400 Bad Request");
  echo json_encode([
    'order_id' => $_POST['order_id'],
    'status' => 'error',
    'ext_id' => '',
    'error' => 'api key is not included'
  ]);
}


