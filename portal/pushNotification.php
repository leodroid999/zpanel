<?php
// all hosts allowed to post, different from rest of scripts. 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");

require "lib/ErrorHandler.php";
require_once 'lib/DB.php';

use Library\DB as DB;

$userID = $_POST['userID'];
$content = $_POST['content'];
$icon = "exclamation-circle";
$isAlerted = false;
$type = "account";
$botToken = '7924767611:AAExdMrywUPKpVIBWMowG_gmm_pmREg50kQ';
$apiURL = "https://api.telegram.org/bot$botToken/sendMessage";

if(!isset($userID) || !isset($content)){
    ErrorHandler::serverError();
}

if(isset($_POST['type'])){
    $type = $_POST['type'];
}

if(isset($_POST['icon'])){
    $badge=$_POST['icon'];
}

if(isset($_POST['isAlerted'])){
    $isAlerted = true;
}


// Connect to the database
$conn = DB::connect();
// Check connection
if (!$conn) {
    error_log("Connection failed: " . mysqli_connect_error($conn));
    ErrorHandler::serverError();
}

$user = DB::getUser($conn, $userID);
if(!$user){
    error_log("Error loading user data: " . mysqli_error($conn));
    ErrorHandler::authError();
}
$user=$user[0];
$added = false;
if($type!="telegram"){
    $added = DB::addNotification($conn,$userID,$type,$icon,$content,$isAlerted);
    if(!$added){
        error_log("Error adding notification : " . mysqli_error($conn));
        ErrorHandler::serverError();
    }
}
if($type=="telegram"){
    $data = array(
        'chat_id' => $user['chatID'],
        'text' => $content
    );
    error_log($data);
    
    $req = curl_init();
    
    curl_setopt($req, CURLOPT_URL, $apiURL);
    curl_setopt($req, CURLOPT_POST, 1);
    curl_setopt($req, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($req);
    $status = curl_getinfo($req, CURLINFO_HTTP_CODE);
    
    if ($status !== 200) {
        error_log($result);
        echo json_encode(array(
            "status"=>"error",
            "message"=>"An error occurred while sending the message."
        ));
        return;
    } 
}
echo json_encode(array(
    "status"=>"ok",
    "message"=>"notification sent successfully!"
));

?>