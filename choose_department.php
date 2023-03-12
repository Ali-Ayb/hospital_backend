<?php

include 'connection_db.php';
include 'jwt.php';

$headers = apache_request_headers();
if (isset($headers['Authorization'])) {

    if (!verifyToken($headers['Authorization'], $secret_key))
        exit;
}

$user_info = user_info($headers['Authorization'], $secret_key);
$user_id = $user_info->user_id;


$department = $_POST['department'];

$user_type_id = $link->prepare('SELECT id FROM departments WHERE name = ? ');
$user_type_id->bind_param('s', $department);
$user_type_id->execute();
$user_type_id->bind_result($department_id);
$user_type_id->fetch();
$user_type_id->close();


$user_type_id = $link->prepare('SELECT hospital_id FROM hospital_users WHERE user_id = ? ');
$user_type_id->bind_param('s', $user_id);
$user_type_id->execute();
$user_type_id->bind_result($hospital_id);
$user_type_id->fetch();
$user_type_id->close();


$user_type_id = $link->prepare('Insert INTO user_departments (user_id, department_id, hospital_id) Value (?,?,?)');
$user_type_id->bind_param('iii', $user_id, $department_id, $hospital_id);

$response = ['response' => $user_type_id->execute()];


echo json_encode($response);
