<?php

$apiKey = "8f7f8b210604cd39289314f3f2726093";
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/x-www-form-urlencoded; charset=UTF-8");

include "../main/database.php";


$data = json_decode(file_get_contents('php://input'), true);


$lead_id                = $data['id'];
$lead_campid            = $data['campaign_id'];
$lead_name              = $data['name'];
$lead_country           = $data['country'];
$lead_phone             = $data['phone'];
$lead_tz                = $data['tz'];
$lead_address           = $data['address'];
$lead_cost              = $data['cost'];
$lead_costDelivery      = $data['cost_delivery'];
$lead_landingCost       = $data['landing_cost'];
$lead_landingCurrency   = $data['landing_currency'];
$lead_checkSum          = $data['check_sum'];
$lead_webId             = $data['web_id'];
$lead_streamId          = $data['stream_id'];
$lead_ip                = $data['ip'];
$lead_userAgent         = $data['user_agent'];
if (isset($data['test'])) {
    $test_status = $data['test'];
    $lead_status = 'test';
    $comment = 'test';
} else {
    $test_status = 0;
}

$lead_status = $lead_status ?? 'expect';
$comment = $comment ?? 'new';

if ($lead_id == "") {
    echo "Enter The Data";
} else {
    if (!isset($data['test'])) {
        $like_lead_phone = "%" . $lead_phone;
        $stmt = $con->prepare("SELECT id, lead_id, campaign_id, web_id, phone, lead_status, comment FROM terraleads 
                                WHERE campaign_id=:campaign_id AND web_id=:web_id AND phone LIKE :phone AND test_status = 0
                                AND !(comment <=> 'duplicate')
                                ORDER BY id DESC LIMIT 1");
        $stmt->bindParam('campaign_id', $lead_campid, PDO::PARAM_INT);
        $stmt->bindParam('phone', $like_lead_phone, PDO::PARAM_STR);
        $stmt->bindParam('web_id', $lead_webId, PDO::PARAM_STR);
        $stmt->execute();
        $find = $stmt->fetch(PDO::FETCH_ASSOC);

        // Catch lead in terraleads
        if ($find) {
            if ($find['lead_status'] === NULL || $find['lead_status'] === 'expect') {

                $lead_from = "terraleads_" . $find['id'] . "_" . $find['lead_id'];

                // API URL
                $url = "https://" . $_SERVER['HTTP_HOST'] . '/api/sendStatus.php';

                // Create a new cURL resource
                $ch = curl_init($url);

                $data = [
                    'lead_from' => $lead_from,
                    'data' => $data,
                    'status' => 'trash',
                    'comment' => 'duplicate',
                    'inserted' => true
                ];
                // Setup request to send json via POST;
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
                echo "Ok";
                return;
            } else {
                $insertLead = $con->prepare("INSERT INTO terraleads(lead_id, campaign_id, name, country, phone, tz, address, cost, cost_delivery, landing_cost, landing_currency, check_sum, web_id, stream_id, ip, user_agent, add_date, test_status, lead_Status, comment) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?)");
                $insertLead->execute([$lead_id, $lead_campid, $lead_name, $lead_country, $lead_phone, $lead_tz, $lead_address, $lead_cost, $lead_costDelivery, $lead_landingCost, $lead_landingCurrency, $lead_checkSum, $lead_webId, $lead_streamId, $lead_ip, $lead_userAgent, $test_status, $lead_status, $comment]);

                if ($insertLead) {
                    if ($lead_status === 'expect' && $comment === 'new') {
                        // API URL
                        $url = 'http://tl-api.com/api/lead/update';

                        // Create a new cURL resource
                        $ch = curl_init($url);

                        // Setup request to send json via POST
                        $data = array(
                            "id" => +$lead_id,
                            "status" => $lead_status,

                            "comment" => $comment,
                            "check_sum" => sha1($lead_id . $lead_status . $comment . $apiKey),
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

                        if($result !== 'OK') {
                            $updated = 0;
                            $stmt = $con->prepare("UPDATE terraleads SET updated=:updated WHERE lead_id=:lead_id");
                            $stmt->bindParam('lead_id', $find['lead_id'], PDO::PARAM_INT);
                            $stmt->bindParam('updated', $updated, PDO::PARAM_INT);
                            $stmt->execute();
                        }
                        // Close cURL resource
                        curl_close($ch);
                    }
                }
            }
        } else {
            $insertLead = $con->prepare("INSERT INTO terraleads(lead_id, campaign_id, name, country, phone, tz, address, cost, cost_delivery, landing_cost, landing_currency, check_sum, web_id, stream_id, ip, user_agent, add_date, test_status, lead_Status, comment) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?)");
            $insertLead->execute([$lead_id, $lead_campid, $lead_name, $lead_country, $lead_phone, $lead_tz, $lead_address, $lead_cost, $lead_costDelivery, $lead_landingCost, $lead_landingCurrency, $lead_checkSum, $lead_webId, $lead_streamId, $lead_ip, $lead_userAgent, $test_status, $lead_status, $comment]);

            if ($insertLead) {
                if ($lead_status === 'expect' && $comment === 'new') {
                    // API URL
                    $url = 'http://tl-api.com/api/lead/update';

                    // Create a new cURL resource
                    $ch = curl_init($url);

                    // Setup request to send json via POST
                    $data = array(
                        "id" => +$lead_id,
                        "status" => $lead_status,

                        "comment" => $comment,
                        "check_sum" => sha1($lead_id . $lead_status . $comment . $apiKey),
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

                    if($result !== 'OK') {
                        $updated = 0;
                        $stmt = $con->prepare("UPDATE terraleads SET updated=:updated WHERE lead_id=:lead_id");
                        $stmt->bindParam('lead_id', $find['lead_id'], PDO::PARAM_INT);
                        $stmt->bindParam('updated', $updated, PDO::PARAM_INT);
                        $stmt->execute();
                    }
                    // Close cURL resource
                    curl_close($ch);
                }
            }
        }
        echo "Ok";
        $data = null;
    }
}
