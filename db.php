<?php
require_once 'config.php';
require_once 'generate_token.php';

$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if (!$conn) {
    die('Not connected : ' . $conn->mysql_error());
}

function get_access_token($conn){
    if($empty = is_table_empty($conn)){
        update_access_token($conn, $empty);
        return select_access_token($conn);
    }else{
        if(is_token_valid($conn)){
            return select_access_token($conn);
        }else{
            update_access_token($conn, $empty=0);
            return select_access_token($conn);
        }
    }
}

function select_access_token($conn){
    $result = mysqli_query($conn, "SELECT * FROM token");
    $row = mysqli_fetch_assoc($result);
    return $row["access_token"];
}

function update_access_token($conn, $empty=1) {
    $new_token_info = create_token();
    $access_token = $new_token_info[0];
    $token_exp_time = (int)$new_token_info[1];
    if($empty){
        if (mysqli_query($conn, "INSERT INTO token (access_token, exp_time) VALUES ('$access_token', '$token_exp_time')"))
            return 1;
    }else{

        if (mysqli_query($conn, "UPDATE token SET access_token = '".$access_token."', exp_time= '".$token_exp_time."'"))
            return 1;
    }
}

function is_token_valid($conn){
    $result = mysqli_query($conn, "SELECT * FROM token");
    $row = mysqli_fetch_assoc($result);
    $curr_time = time();
    $token_exp_time = (int) $row['exp_time'];
    if ($curr_time < $token_exp_time)
        return 1;
    return 0;
}

function is_table_empty($conn) {
    $result = mysqli_query($conn, "SELECT id FROM token");
    if(mysqli_num_rows($result))
        return 0;
    return 1;
}



