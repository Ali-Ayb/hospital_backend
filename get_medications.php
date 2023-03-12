<?php
include "connection_db.php";
include 'jwt.php';

$headers = apache_request_headers();
if (isset($headers['Authorization'])) {

    if (!verifyToken($headers['Authorization'], $secret_key))
        exit;
}


$patient_name = $link->prepare('select name from medicatoins');
$patient_name->execute();

$result = $patient_name->get_result();
$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

echo json_encode($rows);
