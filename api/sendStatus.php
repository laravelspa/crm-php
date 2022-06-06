<?php

$apiKey = "8f7f8b210604cd39289314f3f2726093";
include '../main/database.php';

// Send Status From Admin Panel (api/index.php)
if (isset($_POST['lead_id'], $_POST['id'])) {

  $id       = $_POST['id'];
  $lead_id     = $_POST['lead_id'];
  $status     = trim($_POST['status']) === 'null' ? NULL : trim($_POST['status']);
  $comment     = trim($_POST['comment']);

  $stmt = $con->prepare("UPDATE terraleads SET lead_status=:status,comment=:comment WHERE id=:id");

  $stmt->bindParam("id", $id, PDO::PARAM_INT);
  $stmt->bindParam("comment", $comment, PDO::PARAM_STR);
  $stmt->bindParam("status", $status, PDO::PARAM_STR);

  if ($stmt->execute()) {
    if ($status !== null) {
      // API URL
      $url = 'http://tl-api.com/api/lead/update';

      // Create a new cURL resource
      $ch = curl_init($url);

      // Setup request to send json via POST
      $data = array(
        "id" => $lead_id,
        "status" => $status,
        "comment" => $comment,
        "check_sum" => sha1($lead_id . $status . $comment . $apiKey),
        //"api_key" => $apiKey
      );
      $payload = json_encode($data);

      // Attach encoded JSON string to the POST fields
      curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

      // Set the content type to application/json
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

      // Return response instead of outputting
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

      // Execute the POST request
      $result = curl_exec($ch);

      if ($result !== 'OK') {
        $updated = 0;
        $stmt = $con->prepare("UPDATE terraleads SET updated=:updated WHERE lead_id=:lead_id");
        $stmt->bindParam('lead_id', $find['lead_id'], PDO::PARAM_INT);
        $stmt->bindParam('updated', $updated, PDO::PARAM_INT);
        $stmt->execute();
      }
      // Close cURL resource
      curl_close($ch);

      echo json_encode(['message' => true, 'data' => $data, 'result' => $result]);
    }

    $id = '';
    $lead_id = '';
    $name = '';
    $status = '';
    $comment = '';
    //echo json_encode(['message' => true]);
  } else {
    echo json_encode(['message' => false]);
  }
}


// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/x-www-form-urlencoded; charset=UTF-8");

$data = json_decode(file_get_contents('php://input'), true);

// Send Terraleads Lead via api (employee/orderd.php, employee/pending.php, employee/canceld.php)
if (isset($data['lead_from']) && !isset($data['inserted'])) {
  $lead     = explode('_', $data['lead_from']);
  $from     = $lead[0]; // origin1 / origin2
  $id       = $lead[1]; // Id Primary Key In Terraleads Table
  $lead_id  = $lead[2]; // Lead_id In Terraleads Table
  $status   = $data['status']; // On pending send expect status to terraleads
  $comment  = $data['comment']; // "not answer" | "call again"
  $canceld  = $data['canceld']; // true 

  // Terraleads 
  if ($from === 'origin1') {
    // Start Canceld
    if ($status !== 'confirm' && $status !== 'expect') {
      if (in_array($status, ['invalid_phone_number', 'fake_order', 'expensive', 'changed_mind', 'health_issues', 'consultation', 'cannot_reach_client'])) {
        if (in_array($status, ['invalid_phone_number',  'cannot_reach_client'])) {
          $comment = $status;
          $status = 'trash';
        }
        
        if (in_array($status, ['fake_order'])) {
          $comment = "didn't order / kidding";
          $status = 'trash';
        }

        if (in_array($status, ['expensive', 'changed_mind', 'health_issues', 'consultation'])) {
          $comment = $status;
          $status = 'reject';
        }
      } else {
        $status = 'reject';
        $comment = 'client canceld the order';
      }
    }
    // End Canceld

    $stmt = $con->prepare("UPDATE terraleads SET lead_status=:status,comment=:comment WHERE id=:id");

    $stmt->bindParam("id", $id, PDO::PARAM_INT);
    $stmt->bindParam("comment", $comment, PDO::PARAM_STR);
    $stmt->bindParam("status", $status, PDO::PARAM_STR);

    if ($stmt->execute()) {
      if ($status !== null) {
        // API URL
        $url = 'http://tl-api.com/api/lead/update';

        // Create a new cURL resource
        $ch = curl_init($url);

        // Setup request to send json via POST
        $data = array(
          "id" => $lead_id,
          "status" => $status,
          "comment" => $comment,
          "check_sum" => sha1($lead_id . $status . $comment . $apiKey),
          //"api_key" => $apiKey
        );
        $payload = json_encode($data);

        // Attach encoded JSON string to the POST fields
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        // Set the content type to application/json
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

        // Return response instead of outputting
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute the POST request
        $result = curl_exec($ch);

        if ($result !== 'OK') {
          $updated = 0;
          $stmt = $con->prepare("UPDATE terraleads SET updated=:updated WHERE lead_id=:lead_id");
          $stmt->bindParam('lead_id', $find['lead_id'], PDO::PARAM_INT);
          $stmt->bindParam('updated', $updated, PDO::PARAM_INT);
          $stmt->execute();
        }
        // Close cURL resource
        curl_close($ch);

        echo json_encode(['message' => true, 'lead' => $lead, 'result' => $result]);
      }

      $id = '';
      $lead_id = '';
      $name = '';
      $status = '';
      $comment = '';
      //echo json_encode(['message' => true]);
    } else {
      echo json_encode(['message' => false]);
    }
  }

  // Adcombo 
  if ($from === 'origin2') {
    // Start Cancelled
    if ($status !== 'confirm' && $status !== 'expect') {
      if (in_array($status, ['invalid_phone_number', 'fake_order', 'expensive', 'changed_mind', 'health_issues', 'consultation', 'cannot_reach_client'])) {
        if (in_array($status, ['invalid_phone_number', 'fake_order'])) {
          $extra_state = $status;
          $status = 'trash';
        }

        if (in_array($status, ['expensive', 'changed_mind', 'health_issues', 'consultation', 'cannot_reach_client'])) {
          $extra_state = $status;
          $status = 'cancelled';
        }
      } else {
        $status = 'trash';
        $extra_state = 'trash_other_reason';
      }
    }
    // End Cancelled


    if ($status === 'expect') {
      if ($comment === 'no answer') {
        $status = 'hold';
        $extra_state = 'call_rejected';
      }

      if ($comment === 'call again') {
        $status = 'hold';
        $extra_state = 'recall';
      }
    }

    if ($status === 'confirm') {
      $status = 'confirmed';
      $extra_state = 'approved';
    }

    $stmt = $con->prepare("UPDATE combo SET status=:status,extra_state=:extra_state WHERE id=:id");

    $stmt->bindParam("id", $id, PDO::PARAM_INT);
    $stmt->bindParam("status", $status, PDO::PARAM_STR);
    $stmt->bindParam("extra_state", $extra_state, PDO::PARAM_STR);

    if ($stmt->execute()) {
      echo json_encode(['message' => true]);
    } else {
      echo json_encode(['message' => false]);
    }
  }
}

// Send Status From Employee Insert Duplicate Terraleads (getLead.php['Duplicate'])
if (isset($data['data'], $data['inserted'])) {
  $lead_id                = $data['data']['id'];
  $lead_campid            = $data['data']['campaign_id'];
  $lead_name              = $data['data']['name'];
  $lead_country           = $data['data']['country'];
  $lead_phone             = $data['data']['phone'];
  $lead_tz                = $data['data']['tz'];
  $lead_address           = $data['data']['address'];
  $lead_cost              = $data['data']['cost'];
  $lead_costDelivery      = $data['data']['cost_delivery'];
  $lead_landingCost       = $data['data']['landing_cost'];
  $lead_landingCurrency   = $data['data']['landing_currency'];
  $lead_checkSum          = $data['data']['check_sum'];
  $lead_webId             = $data['data']['web_id'];
  $lead_streamId          = $data['data']['stream_id'];
  $lead_ip                = $data['data']['ip'];
  $lead_userAgent         = $data['data']['user_agent'];

  if (isset($data['data']['test'])) {
    $test_status = $data['data']['test'];
  } else {
    $test_status = 0;
  }

  $status   =  $data['status']; // On pending send expect status to terraleads
  $comment = $data['comment']; // "not answer" | "call again"

  $insertLead = $con->prepare("INSERT INTO terraleads(lead_id, campaign_id, name, country, phone, tz, address, cost, cost_delivery, landing_cost, landing_currency, check_sum, web_id, stream_id, ip, user_agent, add_date, test_status, lead_status, comment, deleted_at) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?, NOW())");
  $insertLead->execute([$lead_id, $lead_campid, $lead_name, $lead_country, $lead_phone, $lead_tz, $lead_address, $lead_cost, $lead_costDelivery, $lead_landingCost, $lead_landingCurrency, $lead_checkSum, $lead_webId, $lead_streamId, $lead_ip, $lead_userAgent, $test_status, $status, $comment]);

  if ($insertLead) {
    if ($status !== null) {
      // API URL
      $url = 'http://tl-api.com/api/lead/update';

      // Create a new cURL resource
      $ch = curl_init($url);

      // Setup request to send json via POST
      $data = array(
        "id" => $lead_id,
        "status" => $status,
        "comment" => $comment,
        "check_sum" => sha1($lead_id . $status . $comment . $apiKey),
        //"api_key" => $apiKey
      );
      $payload = json_encode($data);

      // Attach encoded JSON string to the POST fields
      curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

      // Set the content type to application/json
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

      // Return response instead of outputting
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

      // Execute the POST request
      $result = curl_exec($ch);

      if ($result !== 'OK') {
        $updated = 0;
        $stmt = $con->prepare("UPDATE terraleads SET updated=:updated WHERE lead_id=:lead_id");
        $stmt->bindParam('lead_id', $find['lead_id'], PDO::PARAM_INT);
        $stmt->bindParam('updated', $updated, PDO::PARAM_INT);
        $stmt->execute();
      }
      // Close cURL resource
      curl_close($ch);

      echo json_encode(['message' => true]);
    }

    $id = '';
    $lead_id = '';
    $name = '';
    $status = '';
    $comment = '';
    echo json_encode(['message' => true]);
  } else {
    echo json_encode(['message' => false]);
  }
}
