<?php


require __DIR__ . '/vendor/autoload.php';

use Firebase\JWT\JWT;

$secret_key = "code123";

function verifyToken($jwt, $secret_key)
{
    try {
        $decoded = JWT::decode($jwt, $secret_key, array('HS256'));
        // $is_expired = $decoded->exp < time();
        // if ($is_expired) {
        // }
        return true;
    } catch (\Firebase\JWT\ExpiredException $e) {
        echo 'expired token';
        return false;
    } catch (\Exception $e) {
        echo 'wrong token';
        return false;
    }
}

function user_info($jwt, $secret_key)
{

    return JWT::decode($jwt, $secret_key, array('HS256'));
}
