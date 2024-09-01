<?php
session_start();
include '../main/database.php';
if (isset($_POST['id']) && isset($_POST['name'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $password = '';
    $newpassword = $_POST['password'];
    $oldpassword = $_POST['oldpassword'];
    if ($newpassword !== '') {
        $password = password_hash($newpassword, PASSWORD_DEFAULT);
    } else {
        $password = $oldpassword;
    }
    $is_admin = $_POST['is_admin'];
    if($_POST['id'] === $_SESSION['id']) {
        $_SESSION['is_admin'] = $is_admin;
    }
    $stmt = $con->prepare("UPDATE users SET name = :name,password = :password, is_admin = :is_admin WHERE id = :id");
    $params = array(
        'id' => $id,
        'name' => $name,
        'password' => $password,
        'is_admin' => $is_admin
    );

    if ($stmt->execute($params)) {
        $id = '';
        $name = '';
        $oldpassword = '';
        $newpassword = '';
        $password = '';
        $is_admin = '';
        echo json_encode(["text" => true, 'post' => $_POST, 'params' => $params]);
    } else {
        echo json_encode(["text" => false, 'post' => $_POST, 'params' => $params]);
    }
}
