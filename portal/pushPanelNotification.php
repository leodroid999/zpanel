<?php
// all hosts allowed to post, different from rest of scripts. 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");

require "lib/ErrorHandler.php";
require_once 'lib/DB.php';

use Library\DB as DB;

$panelId = $_POST['panelId'];
$content = $_POST['content'];

function sendMessage($chatID,$content){
    $botToken = '7924767611:AAExdMrywUPKpVIBWMowG_gmm_pmREg50kQ';
    $apiURL = "https://api.telegram.org/bot$botToken/sendMessage";
    $data = array(
        'chat_id' => $chatID,
        'text' => $content
    );

    $req = curl_init();
    curl_setopt($req, CURLOPT_URL, $apiURL);
    curl_setopt($req, CURLOPT_POST, 1);
    curl_setopt($req, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($req);
    $status = curl_getinfo($req, CURLINFO_HTTP_CODE);
    if ($status !== 200) {
        error_log($result);
        return false;
    }
    return true;
}

if(!isset($panelId) || !isset($content)){
    ErrorHandler::serverError();
}

// Connect to the database
$conn = DB::connect();
// Check connection
if (!$conn) {
    error_log("Connection failed: " . mysqli_connect_error());
    ErrorHandler::serverError();
}
$panel = DB::getPanelUser($conn,$panelId);
// send mesage to owner
if(!$panel){
    echo json_encode(array(
        "status"=>"error",
        "message"=>"No such panel"
    ));
    return;
}
$panel=$panel[0];
$chatID = $panel['chatID'];
// send message to owner
sendMessage($chatID,$content);
// get users with access to panel and send message
$users=DB::getPanelAccessList($conn,$panelId,$nodeName);
if($users != null && count($users)>0){
    foreach ($users as &$user) {
        $userChatID = $user['chatID'];
        $delivered=false;
        if($userChatID){
            $result =  sendMessage($userChatID,$content);
            if($result){
                $delivered = true;
            }
        }
        if(!$delivered){
            $added = DB::addNotification($conn,$user['userId'],'settings',"exclamation-circle",
            "Cannot deliver panel notifcations, chatId not set",1);
            if(!$added){
                error_log("Error adding notification : " . mysqli_error($conn));
            }
        }
    }
}
echo json_encode(array(
    "status"=>"ok",
));
?>