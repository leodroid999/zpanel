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

$userpassword = $_SESSION['userSessionPass'];
$userID = $_SESSION['userID'];
$nodeID = $_GET['nodeId'];
$panelID = $_GET['panelId'];
$filter = null;

if(!$userID || !$userpassword){
    ErrorHandler::authError();
}

if(!$nodeID || !$panelID){
    ErrorHandler::serverError();
}

if(isset($_GET['filter'])){
    $filter=$_GET['filter'];
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
if($panel==null){
    $panel = DB::getPanelAddedToById($conn,$user,$panelID);
}
if($panel==null){
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
$nodeHost = $node['nodeId'];
$sql_user = $node['sql_user'];
$nodeSQLUser =  $sql_user ? $sql_user : $node['NodeName'];
$nodeSQLPass = $node['sql_key'];
$panelDB = $panel['panelId'];
$NodeConn = new mysqli($nodeHost, $nodeSQLUser, $nodeSQLPass, $panelDB);
if(!$NodeConn){
    ErrorHandler::serverError();
}

$sessions = DB::getSessions($NodeConn,$filter);
if(!$sessions===null){
    error_log("Error loading session list: " . mysqli_error($NodeConn));
    ErrorHandler::serverError();
}
if(count($sessions)==0){
    echo json_encode(array(
        "status"=>"ok",
        "sessions"=>[],
    ));
}
else{
    echo json_encode(array(
        "status"=>"ok",
        "sessions"=>$sessions,
    ),JSON_INVALID_UTF8_IGNORE);
}
?>