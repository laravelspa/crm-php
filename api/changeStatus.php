<?php
$apiKey = "8f7f8b210604cd39289314f3f2726093";

include "../main/database.php";

$stmt = $con->prepare("SELECT id, lead_id, lead_status, comment FROM terraleads 
                              WHERE updated = 0 AND updated_count < 3 AND test_status = 0
                              ORDER BY id ASC LIMIT 200");
$stmt->execute();

$leads = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($leads !== null && count($leads)) {
  foreach ($leads as $lead) {
    // API URL
    $url = 'http://tl-api.com/api/lead/update';

    // Create a new cURL resource
    $ch = curl_init($url);

    // Setup request to send json via POST
    $data = array(
      "id" => $lead['lead_id'],
      "status" => $lead['lead_status'],
      "comment" => $lead['comment'],
      "check_sum" => sha1($lead['lead_id'] . $lead['lead_status'] . $lead['comment'] . $apiKey)
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

    // Close cURL resource
    curl_close($ch);

    $success = 'Failed';
    $updated = 0;
    if ($result === 'OK') {
      $updated = 1;
      $success = 'Success';
    }
    $stmt = $con->prepare("UPDATE terraleads SET updated_count = updated_count + 1, 
                          updated=:updated WHERE lead_id=:lead_id");
    $stmt->bindParam('lead_id', $lead['lead_id'], PDO::PARAM_INT);
    $stmt->bindParam('updated', $updated, PDO::PARAM_INT);
    $stmt->execute();

    $myfile = fopen("cronlog.txt", "a") or die("Unable to open file!");
    $txt = 'Updated Result: ' . $success . ' & Lead_id = ' . $lead['lead_id'] . ' & Run at: ' . date('Y-m-d H:i:s') . "\n";
    fwrite($myfile, "\n" . $txt);
    fclose($myfile);
  }
}
