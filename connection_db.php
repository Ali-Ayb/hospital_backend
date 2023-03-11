<?php
$host_name = 'localhost';
$database = 'hospital_db';
$username = 'root';
$password = '';

$link = new mysqli($host_name, $username, $password, $database);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers:*");
