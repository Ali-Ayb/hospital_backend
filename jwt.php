<?php


require __DIR__ . '/vendor/autoload.php';

use Firebase\JWT\JWT;

$secret_key = "code123";

function verifyToken($jwt, $secret_key)
{
    try {
        $decoded = JWT::decode($jwt, $secret_key, array('HS256'));
        return true;
    } catch (\Firebase\JWT\ExpiredException $e) {
        return false;
    } catch (\Exception $e) {
        return false;
    }
}

function user_info($jwt, $secret_key)
{

    return JWT::decode($jwt, $secret_key, array('HS256'));
}
