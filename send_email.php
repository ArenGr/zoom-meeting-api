<?php
require_once 'config.php';

function send_email($to, $html_body, $subject) {

    $params = array(
        "personalizations" => [],
        "from" => [
            "email" => MAIL_FROM_ADDRESS,
        ],
        "subject" => $subject,
        "content" => [
            [
                "type"  => "text/html",
                "value" => $html_body
            ]
        ]
    );

    foreach ($to AS $email) {
      $email = str_replace(' ', '', $email);
      array_push($params['personalizations'], ['to' => [['email' => $email]]]);
    }

    $params = json_encode($params);

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => MAIL_URL,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_POSTFIELDS => $params,
        CURLOPT_HTTPHEADER => array(
            "authorization: Bearer ".MAIL_API_KEY,
            "content-type: application/json"
        ),
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);
}


