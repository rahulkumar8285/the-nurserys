<?php


$hub_mode = $_REQUEST['hub_mode'];
$hub_challenge = $_REQUEST['hub_challenge'];
$hub_verify_token = $_REQUEST['hub_verify_token'];

$myTokent = 'thenurserys@12345';

if($hub_verify_token == $myTokent){

// Create an array representing the response
$response = array(
    "status" => 200,
    "message" => "Success",
    "challenge" => $hub_challenge
);

// Convert the response array to JSON format
$json_response = json_encode($response);

// Set the content type header to JSON
header('Content-Type: application/json');

// Send the JSON response
echo $json_response;
}else{
    echo 'vdsvsd';
}
?>


