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

if(!isset($_SESSION['userID'])){
    ErrorHandler::authError();
}
$userID = $_SESSION['userID'];
$sessionID = $_GET['sessionId'];
//frontend will have this saved or go thru panel list,making requests
$nodeID = $_GET['nodeId'];
$panelID = $_GET['panelId'];

if(!$userID){
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
$nodeHost = $node['ip'] ? $node['ip'] : $node['nodeId'];
$sql_user = $node['sql_user'];
$nodeSQLUser =  $sql_user ? $sql_user : $node['NodeName'];
$nodeSQLPass = $node['sql_key'];
$panelDB = $panel['panelId'];
$NodeConn = new mysqli();
$NodeConn->options(MYSQLI_OPT_CONNECT_TIMEOUT, 3);
try{
    $connected = $NodeConn->real_connect($nodeHost, $nodeSQLUser, $nodeSQLPass, $panelDB);
    if(!$connected){
        ErrorHandler::serverError();
    }
}
catch(Exception $e){
    echo json_encode(array(
        "status"=>"ok",
        "sessions"=>null,
    ),JSON_INVALID_UTF8_IGNORE);
    return;
}

$session = DB::getSession($NodeConn,$sessionID);
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
    $session["panelID"]=$panelID;
    $responses = DB::getResponses($NodeConn,$sessionID);
    function filterResponseValues($resp){
        $type=$resp["type"];
        if(str_contains(strtolower($type),"seed")){
            $resp["respons"]="******";
        }
        return $resp;
    }
    if(isset($panel["access"]) && $panel["access"] == "caller"){
        $responses = array_map('filterResponseValues',$responses);
    }
    if($responses){
        $session["responses"]=$responses;
    }
    $options = DB::getOptions($conn,$session['pageID']);
    if($options){
        $session["options"]=$options;
    }
    $blueprint=DB::getBlueprint($conn, $session['pageID']);
    if($blueprint){
        $session["MainField"]=$blueprint[0]["MainField"];
        $session["dataName1"]=$blueprint[0]["dataName1"];
        $session["dataName2"]=$blueprint[0]["dataName2"];
        $session["dataName3"]=$blueprint[0]["dataName3"];
        $session["dataName4"]=$blueprint[0]["dataName4"];
        $session["dataName5"]=$blueprint[0]["dataName5"];
    }
    echo json_encode(array(
        "status"=>"ok",
        "session"=>$session
    ),JSON_INVALID_UTF8_IGNORE);
}
?>