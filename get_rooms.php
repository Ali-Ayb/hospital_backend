<?php
include "connection_db.php";
include 'jwt.php';

$headers = apache_request_headers();
if (isset($headers['Authorization'])) {

    if (!verifyToken($headers['Authorization'], $secret_key))
        exit;
}

$department = $_POST['department'];

$user_type_id = $link->prepare('SELECT id FROM departments WHERE name = ? ');
$user_type_id->bind_param('s', $department);
$user_type_id->execute();
$user_type_id->bind_result($department_id);
$user_type_id->fetch();
$user_type_id->close();

$department = $link->prepare('select room_number from rooms where department_id = ?');
$user_type_id->bind_param('i', $department_id);
$department->execute();

$result = $department->get_result();
$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

echo json_encode($rows);
