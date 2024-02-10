<?php

function generate_newsletter_token($subscription, $action){

    // Create token header as a JSON string
    $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);

    // Create token payload as a JSON string
    $payload = json_encode(['sub' => $subscription->getId(),'act'=>$action]);

    // Encode Header to Base64Url String
    $base64UrlHeader =  base64_encode($header);

    // Encode Payload to Base64Url String
    $base64UrlPayload =  base64_encode($payload);


    // Create Signature Hash
    $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, NEWSLETTER_KEY);
    // Encode Signature to Base64Url String
    $base64UrlSignature =  base64_encode($signature);
    // Create JWT
    $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

    return $jwt;
}

function verify_newsletter_token($token,$action){

    $parts = explode(".",urldecode($token));
    $base64UrlHeader = $parts[0];
    $header_json = base64_decode($base64UrlHeader);
    $header = json_decode($header_json);

    $base64UrlPayload = $parts[1];
    $payload_json = base64_decode($base64UrlPayload);
    $payload = json_decode($payload_json);

    $base64UrlSignature = $parts[2];
    $signature = base64_decode($base64UrlSignature);

    if($header->typ == 'JWT' && $header->alg == 'HS256' && $payload->act == $action){
        // Create Signature Hash
        $signature_check = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, NEWSLETTER_KEY);

        if(hash_equals($signature, $signature_check) ){
            return $payload->sub;
        }
    }
}