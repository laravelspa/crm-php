<?php
include('../main/database.php');
if (isset($_POST['id'], $_POST['pending_comment'], $_POST['pending_reason'], $_POST['pending_time'], $_POST['pending_date'], $_POST['emp_id'])) {

    $id                 = $_POST['id'];
    $pending_reason     = $_POST['pending_reason'];
    $pending_comment    = $_POST['pending_comment'];
    $pending_date       = $_POST['pending_date'];
    $pending_time       = $_POST['pending_time'];
    $emp_id             = $_POST['emp_id'];
    $lead_from          = $_POST['lead_from'];
    $new_time           = date("Y-m-d H:i:s"); // $now + 2 hours

    if (isset($_POST['call_again_attention'])) {
        $emp_id = NULL;
    }

    $stmt = $con->prepare("UPDATE pending SET status = :st,pending_comment = :pc, pending_date = :pd, pending_time = :pt, emp_id=:em, dod = NULL,created_at=:cr WHERE id = :id");
    $params = array(
        'id'  => $id,
        'st'  => $pending_reason,
        'pc'  => $pending_comment,
        'pd'  => $pending_date,
        'pt'  => $pending_time,
        'em'  => $emp_id,
        'cr'  => $new_time
    );

    if ($stmt->execute($params)) {
        // API URL
        $url = "https://" . $_SERVER['HTTP_HOST'] . '/api/sendStatus.php';

        // Create a new cURL resource
        $ch = curl_init($url);

        // Pending
        // status expect
        // comment "call again"
        
        // Pending
        // status expect
        // comment "no answer"
        $data = [
            'lead_from' => $lead_from,
            'status' => 'expect',
            'comment' => $pending_reason === 'not answer' ? 'no answer' : 'call again'
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

        $pending_reason    = '';
        $pending_comment   = '';
        $pending_date    = '';
        $pending_time    = '';
        $id    = '';
        $emp_id    = '';
        $new_time    = '';
        echo json_encode(["text" => true]);
    } else {
        echo json_encode(["text" => false]);
    }
}
