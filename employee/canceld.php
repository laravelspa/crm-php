<?php
include('../main/database.php');

if (isset($_POST['id'], $_POST['dbid'], $_POST['name'], $_POST['phone'], $_POST['pname'], $_POST['prname'], $_POST['prprice'], $_POST['prpieces'], $_POST['prcurrency'], $_POST['emp_id'], $_POST['cr'], $_POST['lead_from'])) {
    $id             = $_POST['id'];
    $dbid           = $_POST['dbid'] === '' ? NULL : $_POST['dbid'];
    $name           = $_POST['name'];
    $phone          = $_POST['phone'];
    $pname          = $_POST['pname'];
    $prname         = $_POST['prname'];
    $prprice        = $_POST['prprice'];
    $prpieces       = $_POST['prpieces'];
    $prcurrency     = $_POST['prcurrency'];
    $emp_id         = $_POST['emp_id'];
    $cr             = $_POST['cr'];
    $lf             = $_POST['lead_from'];
    $web_id         = $_POST['web_id'];
    $added_at       = $_POST['added_at'];
    $new_time       = date("Y-m-d H:i:s"); // $now + 2 hours
    
    $stmt = $con->prepare("INSERT INTO canceld(db_id,pending_id,name,phone,pname,prname,prprice,prpieces,prcurrency,emp_id,status,canceld_at,lead_from,web_id,added_at) VALUES(:dbid,:id,:name,:phone,:pname,:prname,:prprice,:prpieces,:prcurrency,:emp_id,:cr,:canceld_at,:lf,:wid,:ad)");
    
    $stmt->bindParam("id", $id, PDO::PARAM_INT);
    $stmt->bindParam("dbid", $dbid, PDO::PARAM_INT);
    $stmt->bindParam("name", $name, PDO::PARAM_STR);
    $stmt->bindParam("phone", $phone, PDO::PARAM_STR);
    $stmt->bindParam("pname", $pname, PDO::PARAM_STR);
    $stmt->bindParam("prname", $prname, PDO::PARAM_STR);
    $stmt->bindParam("prprice", $prprice, PDO::PARAM_INT);
    $stmt->bindParam("prpieces", $prpieces, PDO::PARAM_INT);
    $stmt->bindParam("prcurrency", $prcurrency, PDO::PARAM_STR);
    $stmt->bindParam("emp_id", $emp_id, PDO::PARAM_INT);
    $stmt->bindParam("cr", $cr, PDO::PARAM_STR);
    $stmt->bindParam("canceld_at", $new_time, PDO::PARAM_STR);
    $stmt->bindParam("lf", $lf, PDO::PARAM_STR);
    $stmt->bindParam("wid", $web_id, PDO::PARAM_STR);
    $stmt->bindParam("ad", $added_at, PDO::PARAM_STR);

    if ($stmt->execute()) {
        $sql = "DELETE FROM pending WHERE id = $id ";
        $stmt = $con->prepare($sql);
        if ($stmt->execute() === true) {
            
            // ["spam", "wrong number", "did not order"]
            // >>> status trash
            // >>> comment same reason

            // ["joke"]
            // >>> status trash
            // >>> bullying

            // ["expensive", "aggressive", "changed his mind"]
            // >>> status rejcet
            // >>> comment same reason
            
            // ["another reason"]
            // >>> status rejcet
            // >>> comment "client canceld the order"

// ====================================================================================== //            
            
            // ['invalid_phone_number']
            // terraleads
                // status => trash
                // comment => invalid_phone_number
            // adcombo
                // status => trash
                // extra_state => invalid_phone_number

            // ['fake_order']
            // terraleads
                // status => trash
                // comment => fake_order
            // adcombo
                // status => trash
                // extra_state => fake_order
            
            // ['another reason']
            // terraleads
                // status rejcet
                // comment "client canceld the order"
            // adcombo
                // status => trash
                // extra_state => trash_other_reason

            // ['expensive']
            // terraleads
                // status rejcet
                // comment "expensive"
            // adcombo
                // status => cancelled
                // extra_state => expensive

            // ['changed_mind']
            // terraleads
                // status rejcet
                // comment "changed_mind"
            // adcombo
                // status => cancelled
                // extra_state => changed_mind
            
            // ['health_issues']
            // terraleads
                // status reject
                // comment "health_issues"
            // adcombo
                // status => cancelled
                // extra_state => health_issues
            
            // ['consultation']
            // terraleads
                // status reject
                // comment "consultation"
            // adcombo
                // status => cancelled
                // extra_state => consultation
            
            // ['cannot_reach_client']
            // terraleads
                // status trash
                // comment "cannot_reach_client"
            // adcombo
                // status => cancelled
                // extra_state => cannot_reach_client

                
            $status = '';
            $comment = '';
            if(in_array($cr, ["spam", "wrong number", "did not order"])) {
                $status = "trash";
                $comment = $cr;
            } else if(in_array($cr, ["joke"])) {
                $status = "trash";
                $comment = 'bullying';
            } else if (in_array($cr, ["expensive", "aggressive", "changed his mind"])) {
                $status = "reject";
                $comment = $cr;
            } else {
                $status = "reject";
                $comment = "client canceld the order";
            }
            
            // API URL
            $url = "https://" . $_SERVER['HTTP_HOST'] . '/api/sendStatus.php';

            // Create a new cURL resource
            $ch = curl_init($url);

            $data = [
                'lead_from' => $lf,
                'status' => $cr,
                'comment' => ''
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

            echo json_encode(['text' => true]);
            
            $id = '';
            $dbid           = '';
            $name           = '';
            $phone          = '';
            $pname          = '';
            $prname         = '';
            $prprice        = '';
            $prpieces       = '';
            $prcurrency     = '';
            $emp_id         = '';
            $cr             = '';
            $date_insert    = '';
            $lf             = '';    
        }
    }
}
