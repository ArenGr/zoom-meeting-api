<?php
require_once 'config.php';

function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function create_token(){
    //build the headers
    $headers = ['alg'=>'HS256','typ'=>'JWT'];
    $headers_encoded = base64url_encode(json_encode($headers));

    //build the payload
    $token_exp = strtotime('+1 day');
    $payload = ['iss'=>API_KEY, 'exp'=>$token_exp];
    $payload_encoded = base64url_encode(json_encode($payload));

    //build the signature
    $signature = hash_hmac('SHA256', "$headers_encoded.$payload_encoded", API_SECRET, true);
    $signature_encoded = base64url_encode($signature);

    //build and return the token
    $token = "$headers_encoded.$payload_encoded.$signature_encoded";
    return array($token, $token_exp);
}
