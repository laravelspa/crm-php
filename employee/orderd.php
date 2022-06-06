<?php
include('../main/database.php');

if (isset($_POST['id'], $_POST['dbid'], $_POST['name'], $_POST['phone'], $_POST['pname'], $_POST['prname'], $_POST['prprice'], $_POST['prpieces'], $_POST['prcurrency'], $_POST['emp_id'], $_POST['address'], $_POST['city'], $_POST['doo'], $_POST['dod'], $_POST['wod'])) {
    $id             = $_POST['id'];
    $dbid           = $_POST['dbid'] === '' ? NULL : $_POST['dbid'];
    $name           = $_POST['name'];
    $phone          = $_POST['phone'];
    $address        = $_POST['address'];
    $city           = $_POST['city'];
    $pname          = $_POST['pname'];
    $prname         = $_POST['prname'];
    $prprice        = $_POST['prprice'];
    $prpieces       = $_POST['prpieces'];
    $prcurrency     = $_POST['prcurrency'];
    $emp_id         = $_POST['emp_id'];
    $doo            = date('Y-m-d');
    $dod            = $_POST['dod'];
    $tod            = $_POST['tod'];
    $comment        = $_POST['com'];
    $wod            = $_POST['wod'];
    $lf             = $_POST['lead_from'];
    $web_id         = $_POST['web_id'];
    $added_at       = $_POST['added_at'];

    $new_time = date("Y-m-d H:i:s"); // $now + 2 hours
    $stmt = $con->prepare("INSERT INTO orderd(db_id,pending_id,name,phone,address,city,pname,prname,prpieces,prprice,prcurrency,emp_id,doo,dod,wod,comment,created_at,tod,lead_from,web_id,added_at) VALUES(:db_id,:id,:name,:phone,:address,:city,:pname,:prname,:prpieces,:prprice,:prcurrency,:emp_id,:doo,:dod,:wod,:comment,:created_at,:tod,:lf,:wid,:ad)");

    $stmt->bindParam("id", $id, PDO::PARAM_INT);
    $stmt->bindParam("db_id", $dbid, PDO::PARAM_INT);
    $stmt->bindParam("name", $name, PDO::PARAM_STR);
    $stmt->bindParam("phone", $phone, PDO::PARAM_STR);
    $stmt->bindParam("address", $address, PDO::PARAM_STR);
    $stmt->bindParam("city", $city, PDO::PARAM_STR);
    $stmt->bindParam("pname", $pname, PDO::PARAM_STR);
    $stmt->bindParam("prname", $prname, PDO::PARAM_STR);
    $stmt->bindParam("prpieces", $prpieces, PDO::PARAM_INT);
    $stmt->bindParam("prprice", $prprice, PDO::PARAM_INT);
    $stmt->bindParam("prcurrency", $prcurrency, PDO::PARAM_STR);
    $stmt->bindParam("emp_id", $emp_id, PDO::PARAM_INT);
    $stmt->bindParam("doo", $doo, PDO::PARAM_STR);
    $stmt->bindParam("dod", $dod, PDO::PARAM_STR);
    $stmt->bindParam("tod", $tod, PDO::PARAM_STR);
    $stmt->bindParam("comment", $comment, PDO::PARAM_STR);
    $stmt->bindParam("wod", $wod, PDO::PARAM_STR);
    $stmt->bindParam("created_at", $new_time, PDO::PARAM_STR);
    $stmt->bindParam("lf", $lf, PDO::PARAM_STR);
    $stmt->bindParam("wid", $web_id, PDO::PARAM_STR);
    $stmt->bindParam("ad", $added_at, PDO::PARAM_STR);

    if ($stmt->execute()) {
        // API URL
        $url = "https://" . $_SERVER['HTTP_HOST'] . '/api/sendStatus.php';

        // Create a new cURL resource
        $ch = curl_init($url);

        $data = [
            'lead_from' => $lf,
            'status' => 'confirm',
            'comment' => "client approved the order"
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


        $sql = "DELETE FROM pending WHERE id = $id ";
        $stmt2 = $con->prepare($sql);
        if ($stmt2->execute()) {
            if (isset($_POST['prname_1']) && !empty($_POST['prname_1'])) {
                $pname_1 = $_POST['pname_1'];
                $prname_1 = $_POST['prname_1'];
                $prprice_1 = $_POST['prprice_1'];
                $prpieces_1 = $_POST['prpieces_1'];
                $prcurrency_1 = $_POST['prcurrency_1'];
                $stmt = $con->prepare("INSERT INTO orderd(db_id,pending_id,name,phone,address,city,pname,prname,prpieces,prprice,prcurrency,emp_id,doo,dod,wod,comment,created_at,tod, lead_from,web_id,added_at) VALUES(:db_id,:id,:name,:phone,:address,:city,:pname,:prname,:prpieces,:prprice,:prcurrency,:emp_id,:doo,:dod,:wod,:comment,:created_at,:tod,:lf,:wid,:ad)");

                $stmt->bindParam("id", $id, PDO::PARAM_INT);
                $stmt->bindParam("db_id", $dbid, PDO::PARAM_INT);
                $stmt->bindParam("name", $name, PDO::PARAM_STR);
                $stmt->bindParam("phone", $phone, PDO::PARAM_STR);
                $stmt->bindParam("address", $address, PDO::PARAM_STR);
                $stmt->bindParam("city", $city, PDO::PARAM_STR);
                $stmt->bindParam("pname", $pname_1, PDO::PARAM_STR);
                $stmt->bindParam("prname", $prname_1, PDO::PARAM_STR);
                $stmt->bindParam("prpieces", $prpieces_1, PDO::PARAM_INT);
                $stmt->bindParam("prprice", $prprice_1, PDO::PARAM_INT);
                $stmt->bindParam("prcurrency", $prcurrency_1, PDO::PARAM_STR);
                $stmt->bindParam("emp_id", $emp_id, PDO::PARAM_INT);
                $stmt->bindParam("doo", $doo, PDO::PARAM_STR);
                $stmt->bindParam("dod", $dod, PDO::PARAM_STR);
                $stmt->bindParam("tod", $tod, PDO::PARAM_STR);
                $stmt->bindParam("comment", $comment, PDO::PARAM_STR);
                $stmt->bindParam("wod", $wod, PDO::PARAM_STR);
                $stmt->bindParam("created_at", $new_time, PDO::PARAM_STR);
                $stmt->bindParam("lf", $lf, PDO::PARAM_STR);
                $stmt->bindParam("wid", $web_id, PDO::PARAM_STR);
                $stmt->bindParam("ad", $added_at, PDO::PARAM_STR);

                if ($stmt->execute()) {
                    if (isset($_POST['prname_2']) && !empty($_POST['prname_2'])) {
                        $pname_2 = $_POST['pname_2'];
                        $prname_2 = $_POST['prname_2'];
                        $prprice_2 = $_POST['prprice_2'];
                        $prpieces_2 = $_POST['prpieces_2'];
                        $prcurrency_2 = $_POST['prcurrency_2'];
                        $stmt = $con->prepare("INSERT INTO orderd(db_id,pending_id,name,phone,address,city,pname,prname,prpieces,prprice,prcurrency,emp_id,doo,dod,wod,comment,created_at,tod,lead_from,web_id,added_at) VALUES(:db_id,:id,:name,:phone,:address,:city,:pname,:prname,:prpieces,:prprice,:prcurrency,:emp_id,:doo,:dod,:wod,:comment,:created_at,:tod,:lf,:wid,:ad)");

                        $stmt->bindParam("id", $id, PDO::PARAM_INT);
                        $stmt->bindParam("db_id", $dbid, PDO::PARAM_INT);
                        $stmt->bindParam("name", $name, PDO::PARAM_STR);
                        $stmt->bindParam("phone", $phone, PDO::PARAM_STR);
                        $stmt->bindParam("address", $address, PDO::PARAM_STR);
                        $stmt->bindParam("city", $city, PDO::PARAM_STR);
                        $stmt->bindParam("pname", $pname_2, PDO::PARAM_STR);
                        $stmt->bindParam("prname", $prname_2, PDO::PARAM_STR);
                        $stmt->bindParam("prpieces", $prpieces_2, PDO::PARAM_INT);
                        $stmt->bindParam("prprice", $prprice_2, PDO::PARAM_INT);
                        $stmt->bindParam("prcurrency", $prcurrency_2, PDO::PARAM_STR);
                        $stmt->bindParam("emp_id", $emp_id, PDO::PARAM_INT);
                        $stmt->bindParam("doo", $doo, PDO::PARAM_STR);
                        $stmt->bindParam("dod", $dod, PDO::PARAM_STR);
                        $stmt->bindParam("tod", $tod, PDO::PARAM_STR);
                        $stmt->bindParam("comment", $comment, PDO::PARAM_STR);
                        $stmt->bindParam("wod", $wod, PDO::PARAM_STR);
                        $stmt->bindParam("created_at", $new_time, PDO::PARAM_STR);
                        $stmt->bindParam("lf", $lf, PDO::PARAM_STR);
                        $stmt->bindParam("wid", $web_id, PDO::PARAM_STR);
                        $stmt->bindParam("ad", $added_at, PDO::PARAM_STR);

                        if ($stmt->execute()) {
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
                            $doo            = '';
                            $dod            = '';
                            $tod            = '';
                            $wod            = '';
                            $lf             = '';
                        }
                    }
                }
            }
            echo json_encode(['text' => true]);
        } else {
            echo json_encode(['text' => false]);
        }
    } else {
        echo json_encode(['text' => false]);
    }
}
