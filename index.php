<?php

$post_data = json_decode(file_get_contents('php://input'), true);

if(!$post_data){
  generate_output([], true, "Invalid data. Only json accepted.");
}

require_once 'db.php';
$token = get_access_token($conn);
$postdata = array( 
				"topic" => isset($post_data['title']) ? $post_data['title'] : "Test room",
				"type" => 2, 
				"start_time" => "", 
				"duration" => isset($post_data['duration']) ? $post_data['duration'] : "30",
			);

if(isset($post_data['password']) && !empty($post_data['password'])){
  $postdata["password"] = $post_data['password'];
}
$subject = $postdata['topic'];
$postdata = json_encode($postdata);

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.zoom.us/v2/users/me/meetings",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => $postdata,
    CURLOPT_HTTPHEADER => array(
        "authorization: Bearer $token",
        "content-type: application/json"
    ),
));
$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);
$res = json_decode($response, true);

if($err && !empty($err)){
  generate_output([], true, $err);
}

if(isset($res['code']) && $res['code']){
  generate_output([], true, $res['message']);
}


$join = $res["join_url"];
$start = $res["start_url"];
$join_pass = $res["password"];

if(isset($post_data['invite_emails']) && is_array($post_data['invite_emails'])){
    $to = [];
    $emails_sent = count($post_data['invite_emails']);

    $html_body = 'You have an invitation to join a zoom meeting.<br><br>Please click <a href="'.$join.'" target="_blank">here to join</a> or follow the link below.<br><br>'.$join.'<br>';
    $html_body .= '<br>Your password is: <b>'.$join_pass.'</b>';

    require_once 'send_email.php';
    send_email($post_data['invite_emails'], $html_body, $subject);

}else{
    $emails_sent = 0;
}


generate_output([
        "join_url" => $res["join_url"],
        "start_url" => $res["start_url"],
        "password" => $res["password"],
        "emails_sent" => $emails_sent
]);


function generate_output($data, $error=false, $message="success"){
  $output = [
    "error" => $error,
    "msg" => $message,
    "data" => $data
  ];

  header('Content-Type: application/json');
  echo json_encode($output);
  exit;
}
