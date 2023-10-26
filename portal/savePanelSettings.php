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

$node = DB::getNodeById($conn,$nodeID);
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

$nodeHost = $panel['nodeId'];
$sql_user = $panel['sql_user'];
$nodeSQLUser =  $sql_user ? $sql_user : $node['NodeName'];
$nodeSQLPass = $panel['sql_key'];
$panelDB = $panel['panelId'];

// Connecting to the Node
$NodeConn = new mysqli($nodeHost, $nodeSQLUser, $nodeSQLPass, $panelDB);

if(!$NodeConn){
    ErrorHandler::serverError();
}
$settings=DB::getPanelSettings($NodeConn,$node['NodeName'],$panelID);
if(!$settings){
    ErrorHandler::serverError();
}
if($settings){
    $settings=$settings[0];
}

if(isset($_POST['antibot_active'])){
    $settings['antibot_active']=$_POST['antibot_active'] ? 1 : 0;
}

if(isset($_POST['mobile_only'])){
    $settings['mobile_only']=$_POST['mobile_only'] ? 1 : 0;
}

if(isset($_POST['Redirect_all'])){
    $settings['Redirect_all']=$_POST['Redirect_all'] ? 1 : 0;
}


$saved=DB::savePanelSettings($NodeConn,$node['NodeName'],$panelID, $settings['antibot_active'], $settings['mobile_only'], $settings['Redirect_all']);
if($saved){
    echo json_encode(array(
        "status"=>"ok",
    ));
}
else{
    error_log("Error updating redirect : " . mysqli_error($NodeConn));
    ErrorHandler::serverError();
}
?>