<?php 
	include "../main/database.php";
	$ids = implode(explode(',',$_POST['ids']),"' ,'");
	$stmt = $con->prepare("SELECT count(*) as count, GROUP_CONCAT(orderd.prpieces,
    '|',orderd.prname,'|', orderd.prprice  SEPARATOR', ') as total, orderd_delivery.*, orderd.* FROM orderd_delivery LEFT JOIN orderd ON orderd_delivery.orderd_id = orderd.id WHERE orderd_delivery.orderd_id IN('".$ids."') GROUP BY orderd.phone");
	$stmt->execute();
	$fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$total = explode(', ',$fetch[0]['total']);
    $one = $total[0] !== '' ? explode('|',$total[0]) : '';
    $two = $total[1] !== '' ? explode('|',$total[1]) : '';
    $three = $total[2] !== '' ? explode('|',$total[2]) : '';
    
	echo json_encode(['text'=>true,'ids'=>$ids,'wod'=>$fetch[0]['wod'],'date'=>$fetch[0]['delivery_date'],'address'=>$fetch[0]['address'],'city'=>$fetch[0]['city'],'name'=>$fetch[0]['name'],'phone'=>$fetch[0]['phone'],'one'=>$one,'two'=>$two,'three'=>$three]);
 ?>