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
