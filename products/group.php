<?php
    include('../main/database.php');
    $getName = $_GET['project'];
    if(isset($getName) && !is_null($getName)) {     
        // Group By Employee
        $stmt = $con->prepare("SELECT pending.emp_id, count(pending.emp_id OR pending.emp_id IS NULL) as count, admins.name FROM pending LEFT JOIN admins ON pending.emp_id = admins.id OR pending.emp_id = NULL WHERE pending.prname ='". $getName."' GROUP BY emp_id");
        $stmt->execute();
        $emp_group = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Group By Status
        $stmt = $con->prepare("SELECT status, count(*) as count FROM pending WHERE prname ='". $getName."' GROUP BY status");
        $stmt->execute();
        $st_group = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Group By Date Of Order
        $stmt = $con->prepare("SELECT doo, count(*) as count FROM pending WHERE prname ='". $getName."' GROUP BY doo");
        $stmt->execute();
        $doo_group = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
?>