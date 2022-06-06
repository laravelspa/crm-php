<?php
include('../main/database.php');
if (isset($_POST['id'], $_POST['dbid'], $_POST['name'], $_POST['phone'], $_POST['address'], $_POST['city'], $_POST['pname'], $_POST['prname'], $_POST['prprice'], $_POST['prpieces'], $_POST['prcurrency'], $_POST['emp_id'], $_POST['wod'], $_POST['doo'], $_POST['dod'])) {
    $id                 = $_POST['id'];
    $dbid               = $_POST['dbid'] === '' ? NULL : $_POST['dbid'];
    $name               = $_POST['name'];
    $phone              = $_POST['phone'];
    $address            = $_POST['address'];
    $city               = $_POST['city'];
    $pname              = $_POST['pname'];
    $prname             = $_POST['prname'];
    $prprice            = $_POST['prprice'];
    $prpieces           = $_POST['prpieces'];
    $prcurrency         = $_POST['prcurrency'];
    $emp_id             = $_POST['emp_id'];
    $wod                = $_POST['wod'];
    $com                = $_POST['com'];
    $doo                = date('Y-m-d');
    $dod                = $_POST['dod'];
    $tod                = $_POST['tod'];
    $lf                 = $_POST['lead_from'];
    $new_time = date("Y-m-d H:i:s"); // $now + 2 hours

    $stmt = $con->prepare("UPDATE pending SET db_id=:db_id,emp_id=:em,name=:n,phone=:p,address=:add,city=:c,prprice=:prp,prpieces=:prpi,prcurrency=:prc,pending_comment=:pcom,status=:st,doo=:doo,dod=:dod,created_at= :created_at,tod=:tod,lead_from=:lf WHERE id = :id");
    $params = array(
        'id' => $id,
        'db_id' => $dbid,
        'em' => $emp_id,
        'st' => 'dod',
        'n' => $name,
        'p' => $phone,
        'add' => $address,
        'c' => $city,
        'prp' => $prprice,
        'prpi' => $prpieces,
        'prc' => $prcurrency,
        'pcom' => $com,
        'doo' => $doo,
        'dod' => $dod,
        'tod' => $tod,
        'created_at' => $new_time,
        'lf' => $lf
    );

    if ($stmt->execute($params)) {
        // API URL
        $url = "https://" . $_SERVER['HTTP_HOST'] . '/api/sendStatus.php';

        // Create a new cURL resource
        $ch = curl_init($url);

        $data = [
            'lead_from' => $lf,
            'status' => 'expect',
            'comment' => "call again"
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
        $result = curl_exec($ch);;
        // Close cURL resource
        curl_close($ch);

        $id             = '';
        $dbid           = '';
        $name           = '';
        $phone          = '';
        $address        = '';
        $city           = '';
        $pname          = '';
        $prname         = '';
        $prprice        = '';
        $prpieces       = '';
        $prcurrency     = '';
        $emp_id         = '';
        $wod            = '';
        $com            = '';
        $doo            = '';
        $dod            = '';
        $tod            = '';
        $lf             = '';
        echo json_encode(["text" => true]);
    }
}
