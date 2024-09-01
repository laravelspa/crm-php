<?php
session_start();
$sessionID = $_SESSION['id'];

include('../main/database.php');
if (isset($_POST['date'], $_POST['facility_name'], $_POST['facility_activity'], $_POST['facility_address'], $_POST['mobile'], $_POST['commercial_register'], $_POST['no_civil_registry'], $_POST['internal_cameras'], $_POST['external_cameras'], $_POST['recording_device'], $_POST['recording_duration'], $_POST['storage_disk'], $_POST['display'], $_POST['other_specifications'])) {
    $date = $_POST['date'];
    $facility_name = $_POST['facility_name'];
    $facility_activity = $_POST['facility_activity'];
    $facility_address = $_POST['facility_address'];
    $mobile = $_POST['mobile'];
    $commercial_register = $_POST['commercial_register'];
    $no_civil_registry = $_POST['no_civil_registry'];
    $internal_cameras = $_POST['internal_cameras'];
    $external_cameras = $_POST['external_cameras'];
    $recording_device = $_POST['recording_device'];
    $recording_duration = $_POST['recording_duration'];
    $storage_disk = $_POST['storage_disk'];
    $display = $_POST['display'];
    $other_specifications = $_POST['other_specifications'];
    $user_id = $sessionID;


    $stmt = $con->prepare("INSERT INTO certificates(
        date,
        facility_name, 
        facility_activity, 
        facility_address, 
        mobile, 
        commercial_register, 
        no_civil_registry, 
        internal_cameras, 
        external_cameras, 
        recording_device, 
        recording_duration, 
        storage_disk, 
        display, 
        other_specifications,
        user_id
    ) 
    VALUES(:dt,:fn,:fac,:fad,:mob,:cr,:ncr,:ic,:ec,:rdev,:rdur,:sd,:dis,:osp,:uid)");

    $stmt->bindParam("dt", $date, PDO::PARAM_STR);
    $stmt->bindParam("fn", $facility_name, PDO::PARAM_STR);
    $stmt->bindParam("fac", $facility_activity, PDO::PARAM_STR);
    $stmt->bindParam("fad", $facility_address, PDO::PARAM_STR);
    $stmt->bindParam("mob", $mobile, PDO::PARAM_STR);
    $stmt->bindParam("cr", $commercial_register, PDO::PARAM_STR);
    $stmt->bindParam("ncr", $no_civil_registry, PDO::PARAM_STR);
    $stmt->bindParam("ic", $internal_cameras, PDO::PARAM_STR);
    $stmt->bindParam("ec", $external_cameras, PDO::PARAM_STR);
    $stmt->bindParam("rdev", $recording_device, PDO::PARAM_STR);
    $stmt->bindParam("rdur", $recording_duration, PDO::PARAM_STR);
    $stmt->bindParam("sd", $storage_disk, PDO::PARAM_STR);
    $stmt->bindParam("dis", $display, PDO::PARAM_STR);
    $stmt->bindParam("osp", $other_specifications, PDO::PARAM_STR);
    $stmt->bindParam("uid", $user_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $id = $con->lastInsertId();
        $serial_number = sprintf('%07d', $id);
        $stmt = $con->prepare("UPDATE certificates SET serial_number = :sn WHERE id = :id");
        $params = array(
            'id' => $id,
            'sn' => $serial_number,
        );
        $stmt->execute($params);
        
        $serial_number = '';
        $date = '';
        $facility_name = '';
        $facility_activity = '';
        $facility_address = '';
        $mobile = '';
        $commercial_register = '';
        $no_civil_registry = '';
        $internal_cameras = '';
        $external_cameras = '';
        $recording_device = '';
        $recording_duration = '';
        $storage_disk = '';
        $display = '';
        $other_specifications = '';
        echo json_encode(['text' => true]);
    } else {
        echo json_encode(['text' => false]);
    }
}
