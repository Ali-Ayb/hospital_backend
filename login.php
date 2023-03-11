<?php
include "connection_db.php";
include "jwt.php";

require __DIR__ . '/vendor/autoload.php';

use Firebase\JWT\JWT;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['email']) && isset($_POST['password'])) {

        function validate($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        $email = validate($_POST['email']);
        $password = validate($_POST['password']);

        $sql = "SELECT * FROM users JOIN user_types On users.user_type_id = user_types.id WHERE email='$email'";
        $result = mysqli_query($link, $sql);
        $response = [];


        if (mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);
            $password = hash('sha256', $password . $row['salt']);

            if ($row['email'] === $email && $row['password'] === $password) {

                $payload = array(
                    "user_id" => $row['id'],
                    "email" => $email,
                    "role" => $row['role']
                );
                $jwt = JWT::encode($payload, $secret_key);

                echo json_encode(array(
                    "jwt" => $jwt
                ));
            }
        }
    }
}
