<?php
    include('../main/database.php');
    $getName = $_GET['project'];
    if(isset($getName) && !is_null($getName)) {     
        // Group By Employee
        $stmt = $con->prepare("SELECT pending_dublicate.emp_id, count(pending_dublicate.emp_id OR pending_dublicate.emp_id IS NULL) as count, admins.name FROM pending_dublicate LEFT JOIN admins ON pending_dublicate.emp_id = admins.id OR pending_dublicate.emp_id = NULL WHERE pending_dublicate.pname ='". $getName."' GROUP BY emp_id");
        $stmt->execute();
        $emp_group = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Group By Status
        $stmt = $con->prepare("SELECT status, count(*) as count FROM pending_dublicate WHERE pname ='". $getName."' GROUP BY status");
        $stmt->execute();
        $st_group = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Group By Date Of Order
        $stmt = $con->prepare("SELECT doo, count(*) as count FROM pending_dublicate WHERE pname ='". $getName."' GROUP BY doo");
        $stmt->execute();
        $doo_group = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
?>