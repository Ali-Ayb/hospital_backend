<?php
include "connection_db.php";
include 'jwt.php';

$headers = apache_request_headers();
if (isset($headers['Authorization'])) {

    if (!verifyToken($headers['Authorization'], $secret_key))
        exit;
}

$department = $link->prepare('select name from departments');
$department->execute();

$result = $department->get_result();
$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

echo json_encode($rows);
