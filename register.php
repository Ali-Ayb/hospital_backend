<?php

include 'connection_db.php';
$user_name_err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['password'])) {
        function generateRandomString($length = 10)
        {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
        }

        function calculateAgeFromBirth($birth_date)
        {
            $today = date("Y-m-d");
            $diff = date_diff(date_create($birth_date), date_create($today));
            $age = $diff->format('%y');
            return $age;
        };

        $user_type = 'patient';

        $stmt = mysqli_prepare($link, "SELECT id FROM user_types WHERE role = ?");
        mysqli_stmt_bind_param($stmt, "s", $user_type);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $user_type_id);
        mysqli_stmt_fetch($stmt);


        $salt = generateRandomString(5);
        $email = $_POST['email'];
        $password = $_POST['password'];
        $hashedPassword = hash('sha256', $password . $salt);
        $name = $_POST['name'];
        $birth = $_POST['birth_date'];
        $deleted = 0;
        mysqli_stmt_free_result($stmt);

        $stmt = mysqli_prepare($link, "SELECT email FROM users WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $err =  'email Already exists';
            $response = [
                "response" => $err
            ];
            echo json_encode([$err]);
        } else {
            $sql = "INSERT INTO users ( name, email, password, salt, birth_date, user_type_id, deleted) VALUES (?, ?, ?, ?, ?, ?, ?)";
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "sssssii", $name, $email, $hashedPassword, $salt, $birth, $user_type_id, $deleted);
                if (mysqli_stmt_execute($stmt)) {
                    $success = "success";
                    $response['status'] = "success";

                    echo json_encode($response);
                } else {
                    $err =  "Error Execute";
                    $response = [
                        "response" => $err
                    ];
                    echo json_encode([$err]);
                }
            } else {
                $err =  "Error prepare";
                $response = [
                    "response" => $err
                ];
                echo json_encode([$err]);
            }
        }
    }
}
