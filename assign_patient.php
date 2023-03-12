<?php

include 'connection_db.php';
include 'jwt.php';

$headers = apache_request_headers();
if (isset($headers['Authorization'])) {

    if (!verifyToken($headers['Authorization'], $secret_key))
        exit;
}

$user_info = user_info($headers['Authorization'], $secret_key);
$role = $user_info->role;
if ($role != 'admin') {
    echo 'Not Authorized';
    exit;
}

$patient = $_POST['patient'];
$hospital = $_POST['hospital'];

$user_type_id = $link->prepare('SELECT id FROM users WHERE name = ? ');
$user_type_id->bind_param('s', $patient);
$user_type_id->execute();
$user_type_id->bind_result($patient_id);
$user_type_id->fetch();
$user_type_id->close();

$user_type_id = $link->prepare('SELECT id FROM hospitals WHERE name = ? ');
$user_type_id->bind_param('s', $hospital);
$user_type_id->execute();
$user_type_id->bind_result($hospital_id);
$user_type_id->fetch();
$user_type_id->close();

$is_active = true;
$date_joined = date("Y-m-d");


$user_type_id = $link->prepare('Insert INTO hospital_users (hospital_id, user_id, is_active, date_joined) Value (?,?,?,?)');
$user_type_id->bind_param('iiss', $hospital_id, $patient_id, $is_active, $date_joined);

$response = ['response' => $user_type_id->execute()];


echo json_encode($response);
