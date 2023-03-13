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

$medication = $_POST['medication'];
$quantity = $_POST['quantity'];

$user_type_id = $link->prepare('SELECT id FROM medicatoins WHERE name = ? ');
$user_type_id->bind_param('s', $medication);
$user_type_id->execute();
$user_type_id->bind_result($medication_id);
$user_type_id->fetch();
$user_type_id->close();

$user_type_id = $link->prepare('Insert INTO users_has_medications (user_id, medicatoin_id, quantity) Value (?,?,?)');
$user_type_id->bind_param('iis', $user_id, $medication_id, $quantity);

$response = ['response' => $user_type_id->execute()];


echo json_encode($response);
