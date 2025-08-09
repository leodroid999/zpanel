<?php

include "cors.php";
require "lib/ErrorHandler.php";
require_once 'lib/DB.php';

use Library\DB as DB;

session_start();

$userID = $_SESSION['userID'];
$nodeName = null;
$panelID = null;

if(!isset($userID)){
    ErrorHandler::authError();
    return;
}

if(!isset($_GET['panelId'])){
    ErrorHandler::serverError();
    return;
}
else{
    $panelID = $_GET["panelId"];
}

if(!isset($_GET['nodeName'])){
    ErrorHandler::serverError();
    return;
}
else{
    $nodeName = $_GET["nodeName"];
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

if($access=="caller"){
  return;
}
$panel = DB::getPanel($conn,$user,$panelID);
$owner=true;

if(!$panel){
    $owner=false;
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
if (!$owner){
  $users=DB::getPanelAccessList($conn,$panelID);
  $access = null;
  foreach ($users as $u){
    if($u["username"] == $user["username"]){
      $access = $u["access"];
      break;
    }
  }
  if(!$access || $access=="caller"){
    echo "No Access!";
    return;
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

echo("username: ")
?>