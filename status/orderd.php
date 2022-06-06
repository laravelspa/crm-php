<?php 

include('../main/database.php');

if(isset($_POST['edit_id'],$_POST['name'],$_POST['phone'],$_POST['prname'],$_POST['prprice'],$_POST['prpieces'],$_POST['prcurrency'],$_POST['address'],$_POST['city'],$_POST['doo'],$_POST['dod'],$_POST['comment'],$_POST['wayoforder'])) {

    $edit_id = $_POST['edit_id'];

    $phone = $_POST['phone'];

    $name = $_POST['name'];

    $address = $_POST['address'];

    $city = $_POST['city'];

    $doo = $_POST['doo'];

    $dod = $_POST['dod'];

    $prname = $_POST['prname'];

    $prpi = $_POST['prpieces'];

    $prp = $_POST['prprice'];

    $prc = $_POST['prcurrency'];

    $wod = $_POST['wayoforder'];

    $com = $_POST['comment'];

    $stmt = $con->prepare("UPDATE orderd SET name=:n,phone=:p,city=:c,address=:add,dod=:dod,prpieces=:prpieces,prprice=:prprice,prcurrency=:prcurrency,wod=:wod,comment=:comment WHERE id = :edit_id");

    $stmt->bindParam("edit_id", $edit_id,PDO::PARAM_INT);

    $stmt->bindParam("n", $name,PDO::PARAM_STR);

    $stmt->bindParam("p", $phone,PDO::PARAM_STR);

    $stmt->bindParam("add", $address,PDO::PARAM_STR);

    $stmt->bindParam("c", $city,PDO::PARAM_STR);

    $stmt->bindParam("dod", $dod,PDO::PARAM_STR);

    $stmt->bindParam("prpieces", $prpi,PDO::PARAM_INT);

    $stmt->bindParam("prprice", $prp,PDO::PARAM_STR);

    $stmt->bindParam("prcurrency", $prc,PDO::PARAM_STR);

    $stmt->bindParam("wod", $wod,PDO::PARAM_STR);

    $stmt->bindParam("comment", $com,PDO::PARAM_STR);

    if($stmt->execute()) {

        $edit_id = '';

        $phone = '';

        $name = '';

        $address = '';

        $city = '';

        $doo = '';

        $dod = '';

        $prname = '';

        $prpi = '';

        $prp = '';

        $prc = '';

        $wod = '';

        $com = '';

       echo json_encode(['text' => true]);

    }

}

?>