<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

include "cors.php";
require "lib/ErrorHandler.php";
require_once 'lib/DB.php';

use Library\DB as DB;

session_start();

$userID = $_SESSION['userID'];
$nodeID = $_GET['nodeId'];
$panelID = $_GET['panelId'];

if(!$userID){
    ErrorHandler::authError();
}

if(!isset($nodeID) || !isset($panelID)){
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

$panel = DB::getPanel($conn,$user,$panelID,$nodeID);

if(!$panel){
    $panel = DB::getPanelAddedToById($conn,$user,$panelID);
}
if(!$panel){
    ErrorHandler::serverError();
}
if($panel){
    $panel=$panel[0];
    if(isset($panel['nodeID'])){
        $panel["nodeId"] = $panel["nodeID"];
    }
}

$node = DB::getNodeById($conn,$panel["nodeId"]);
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
if(!isset($settings)){
    error_log("Error loading panel settings: " . mysqli_error($NodeConn));
    ErrorHandler::serverError();
}

if(count($settings)==0){
    echo json_encode(array(
        "status"=>"ok",
        "settings"=>[]
    ));
}
else{
    if(isset($panel["access"])){
        $settings[0]["access"]=$panel["access"];
    }
    echo json_encode(array(
        "status"=>"ok",
        "settings"=>$settings[0]
    ));
}
?>