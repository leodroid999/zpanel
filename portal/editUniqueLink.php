<?php

include "cors.php";
require "lib/ErrorHandler.php";
require_once 'lib/DB.php';

use Library\DB as DB;

session_start();

$userID = $_SESSION['userID'];
$nodeName = null;
$panelID = null;
$linkId = null;
$data = null;


if(!isset($userID)){
    ErrorHandler::authError();
    return;
}

if(!isset($_POST['panelId'])){
    ErrorHandler::serverError();
    return;
}
else{
    $panelID = $_POST["panelId"];
}

if(!isset($_POST['nodeName'])){
    ErrorHandler::serverError();
    return;
}
else{
    $nodeName = $_POST["nodeName"];
}

if(isset($_POST['linkId'])){
    $linkId=$_POST['linkId'];
}
else{
    echo json_encode(array(
        "status"=>"error",
        "message"=>"missing linkId"
    ));
    return;
}

if(isset($_POST['data'])){
    $data=$_POST['data'];
}
else{
    echo json_encode(array(
        "status"=>"error",
        "message"=>"missing data"
    ));
    return;
}
// Connect to the database
$conn = DB::connect();

// Check connection
if (!$conn) {
    error_log("Connection failed: " . mysqli_connect_error());
    ErrorHandler::serverError();
}

$user = DB::getUser($conn, $userID,true);
if(!$user){
    error_log("Error loading user data: " . mysqli_error($conn));
    ErrorHandler::authError();
}
$user =  $user[0];

$panel = DB::getPanel($conn,$user,$panelID);
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
$nodeHost = $node['ip'] ? $node['ip'] : $node['nodeId'];
$sql_user = $node['sql_user'];
$nodeSQLUser =  $sql_user ? $sql_user : $node['NodeName'];
$nodeSQLPass = $node['sql_key'];
$panelDB = $panel['panelId'];
$NodeConn = new mysqli($nodeHost, $nodeSQLUser, $nodeSQLPass, $panelDB);
if(!$NodeConn){
    ErrorHandler::serverError();
}

$result = DB::updateUniqueLink($NodeConn,$linkId,$data);
error_log(var_export($result,true));

if($result){
    echo json_encode(array(
        "status"=>"ok",
        "message"=>"Saved sucessfully.",
    ));
}
else{
    echo json_encode(array(
        "status"=>"error",
        "message"=>"Error saving uniquelink.",
    ));
}
?>