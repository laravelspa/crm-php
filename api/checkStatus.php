<?php

$apiKey = "8f7f8b210604cd39289314f3f2726093";
// required headers
// header("Access-Control-Allow-Origin: *");
// header("Content-Type: application/x-www-form-urlencoded; charset=UTF-8");

include "../main/database.php";

$data = json_decode(file_get_contents('php://input'), true);

$lead_id = $data['id'];


if ($lead_id == "") {
  echo "Enter The Data";
} else {
  $stmt = $con->prepare("SELECT lead_id, lead_status, comment FROM terraleads 
                                WHERE lead_id=:lead_id AND test_status = 0
                                ORDER BY id DESC LIMIT 1");
  $stmt->bindParam('lead_id', $lead_id, PDO::PARAM_INT);
  $stmt->execute();
  $find = $stmt->fetch(PDO::FETCH_OBJ);

  if ($find) {
    echo json_encode([
      "id" => $find->lead_id,
      "status" => $find->lead_status,
      "comment" => $find->comment,
      "check_sum" => sha1($find->lead_id . $find->lead_status . $find->comment . $apiKey),
    ]);
  }
}



// $stmt = $con->prepare("SELECT web_id, lead_id, lead_status, comment FROM terraleads 
//                       WHERE Date(add_date) = '2022-05-07' AND test_status = 0
//                     ");
// $stmt->execute();
// $find = $stmt->fetchAll(PDO::FETCH_ASSOC);

// foreach ($find as $f) {
//   $url = 'http://tl-api.com/api/lead/update';

//   // Create a new cURL resource
//   $ch = curl_init($url);

//   // Setup request to send json via POST
//   $data = array(
//     "id" => $f['lead_id'],
//     "status" => $f['lead_status'],
//     "comment" => $f['comment'],
//     "check_sum" => sha1($f['lead_id'] . $f['lead_status'] . $f['comment'] . $apiKey),
//     //"api_key" => $apiKey
//   );
//   $payload = json_encode($data);

//   // Attach encoded JSON string to the POST fields
//   curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

//   // Set the content type to application/json
//   curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

//   // Return response instead of outputting
//   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//   // Execute the POST request
//   $result = curl_exec($ch);

//   echo json_encode(['lead_id' => $f['lead_id'], 'result' => $result]) . '<br>';
// }


// $stmt = $con->prepare("SELECT updated, lead_status, comment, lead_id FROM `terraleads` WHERE lead_id IN (
// )");
// $stmt->execute();
// $find = $stmt->fetchAll(PDO::FETCH_ASSOC);
// echo '<pre>';
// var_dump($find);
// echo '</pre>';

// $stmt2 = $con->prepare("SELECT status, SUBSTRING_INDEX(lead_from, '_', -1) AS lead_id FROM `canceld` WHERE SUBSTRING_INDEX(lead_from, '_', -1) IN (
//   )");
// $stmt2->execute();
// $find2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

// echo '<pre>';
// var_dump($find2);
// echo '</pre>';

// $comment = '';
// $status = '';

// foreach ($find2 as $f) {
//   if (in_array($f['status'], ['invalid_phone_number', 'fake_order', 'expensive', 'changed_mind', 'health_issues', 'consultation', 'cannot_reach_client'])) {
//     if (in_array($f['status'], ['invalid_phone_number',  'cannot_reach_client'])) {
//       $comment = $f['status'];
//       $status = 'trash';
//     }

//     if (in_array($f['status'], ['fake_order'])) {
//       $comment = 'spam';
//       $status = 'trash';
//     }

//     if (in_array($f['status'], ['expensive', 'changed_mind', 'health_issues', 'consultation'])) {
//       $comment = $f['status'];
//       $status = 'reject';
//     }
//   } else {
//     $comment = 'client canceld the order';
//     $status = 'reject';
//   }

//   echo 'comment: ' . $comment . '<br>';
//   echo 'status: ' . $status . '<br>';


//   $stmt = $con->prepare("UPDATE terraleads SET lead_status=:status,comment=:comment, updated=0 WHERE lead_id=:lead_id");

//   $stmt->bindParam("lead_id", $f['lead_id'], PDO::PARAM_INT);
//   $stmt->bindParam("comment", $comment, PDO::PARAM_STR);
//   $stmt->bindParam("status", $status, PDO::PARAM_STR);

//   if($stmt->execute()) {
//     echo 'Updated Lead ID : ' . $f['lead_id'] . '<br>';
//   }
// }