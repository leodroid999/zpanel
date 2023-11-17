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
$sessionID = $_GET['sessionId'];
//frontend will have this saved or go thru panel list,making requests
$nodeID = $_GET['nodeId'];
$panelID = $_GET['panelId'];

if(!$userID || !$userpassword){
    ErrorHandler::authError();
}

if(!$nodeID || !$panelID){
    ErrorHandler::serverError();
}

if(!$sessionID){
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
$node = $node[0];;
$nodeHost = $node['nodeId'];
$sql_user = $node['sql_user'];
$nodeSQLUser =  $sql_user ? $sql_user : $node['NodeName'];
$nodeSQLPass = $node['sql_key'];
$panelDB = $panel['panelId'];
$NodeConn = new mysqli($nodeHost, $nodeSQLUser, $nodeSQLPass, $panelDB);

if(!$NodeConn){
    ErrorHandler::serverError();
}
$getPageTable = DB::checkPageTableExists($NodeConn,$node['NodeName']);
$session = DB::getSession($NodeConn,$node['NodeName'],$sessionID,$getPageTable);
if(!isset($session)){
    error_log("Error loading session list: " . mysqli_error($NodeConn));
    ErrorHandler::serverError();
}
if(count($session)==0){
    echo json_encode(array(
        "status"=>"ok",
        "session"=>null
    ));
}
else{
    $session=$session[0];
    $responses = DB::getResponses($NodeConn,$sessionID);
    if($responses){
        $session["responses"]=$responses;
    }
    $options = DB::getOptions($NodeConn,$panel['NodeName'],$session['pageID']);
    if($options){
        $session["options"]=$options;
    }
    echo json_encode(array(
        "status"=>"ok",
        "session"=>$session
    ));
}
?>