<?php

include "cors.php";
require "lib/ErrorHandler.php";
require_once 'lib/DB.php';

use Library\DB as DB;

session_start();

$userID = $_SESSION['userID'];
$sessionID = $_POST['sessionId'];
$nodeID = $_POST['nodeId'];
$panelID = $_POST['panelId'];

$bookmarked = false;

if(!$userID){
    ErrorHandler::authError();
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

$updated=DB::deleteSession($NodeConn,$sessionID);
if($updated){
    echo json_encode(array(
        "status"=>"ok",
    ));
}
else{
    error_log("Error saving bookmark : " . mysqli_error($NodeConn));
    ErrorHandler::serverError();
}
?>