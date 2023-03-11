<?php
include "connection_db.php";

$patient_name = $link->prepare('select name from hospitals');
$patient_name->execute();

$result = $patient_name->get_result();
$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

echo json_encode($rows);
