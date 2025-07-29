<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
error_reporting(E_ALL);

include "cors.php";
require "lib/ErrorHandler.php";
require_once 'lib/DB.php';

use Library\DB as DB;

session_start();

$userID = $_SESSION['userID'];
$nodeID = $_POST['nodeId'];
$panelID = $_POST['panelId'];

if(!$userID){
    ErrorHandler::authError();
}

if(!$nodeID || !$panelID){
    ErrorHandler::serverError();
}


// Connect to the database
$conn = DB::connect();

// Check connection
if (!$conn) {
    error_log("Connection failed: " . mysqli_connect_error());
    ErrorHandler::serverError();
}

$user = DB::getUser($conn, $userID);
$user=DB::getUser($conn,$_SESSION['userID']);
if(!$user){
    error_log("Error loading user data: " . mysqli_error($conn));
    ErrorHandler::authError();
}

$user=$user[0];

$node = DB::getNode($conn,$nodeID);

if(!$node){
    ErrorHandler::serverError();
}
$node = $node[0];


$panel = DB::getPanel($conn,$user,$panelID,$node['nodeId']);
if(!$panel){
    $panel = DB::getPanelAddedToById($conn,$user,$panelID);
}
if(!$panel){
    ErrorHandler::serverError();
}
if($panel){
    $panel=$panel[0];
}

$node = DB::getNodeById($conn,$node['nodeId']);
if(!$node){
    ErrorHandler::serverError();
}
$node = $node[0];
$nodeHost = $node['ip'] ? $node['ip'] : $node['nodeId'];
$sql_user = $node['sql_user'];
$nodeSQLUser =  $sql_user ? $sql_user : $node['NodeName'];
$nodeSQLPass = $node['sql_key'];
$panelDB = $node['NodeName'];
$NodeConn = new mysqli($nodeHost, $nodeSQLUser, $nodeSQLPass, $panelDB);

$settings=DB::getPanelSettings($NodeConn,$panelID);
if(!$settings){
    ErrorHandler::serverError();
}
if($settings){
    $settings=$settings[0];
}

$availableSettings=["Mobile_Only","Enable_Captcha","Enable_Turnstile","Enable_Panel_ChatId","ChatId","Redirect_All","CFSiteKey","CFSiteSecret"];
$newSettings=[];
foreach($availableSettings as $settingname){
    if(isset($_POST[$settingname])){
        $newSettings[$settingname] = $_POST[$settingname];
    }
}
$saved=DB::savePanelSettings($NodeConn,$panelID,$newSettings);
if($saved){
    echo json_encode(array(
        "status"=>"ok",
    ));
}
else{
    error_log("Error updating redirect : " . mysqli_error($conn));
    ErrorHandler::serverError();
}
?>