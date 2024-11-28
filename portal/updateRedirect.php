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
$sessionID = $_POST['sessionId'];
//frontend will have this saved or go thru panel list,making requests
$nodeID = null;
$panelID = null;
$newRedirect = null;
if($sessionID!="testsession"){
    $nodeID = $_POST['nodeId'];
    $panelID = $_POST['panelId'];
}
$setError = false;

if(isset($_POST['seterror'])){
    $setError = $_POST['seterror'];
}

$newRedirect = $_POST['newRedirect'];

if(!$userID || !$userpassword){
    ErrorHandler::authError();
}

if((!$nodeID || !$panelID) && $sessionID!="testsession"){
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

//if test session , just update the redirect in editor table

if($sessionID=="testsession"){
    $updated=DB::updateTestRedirect($conn,$user,$newRedirect,$setError);
    if($updated){
        echo json_encode(array(
            "status"=>"ok",
            "Next_Redirect"=>$newRedirect
        ));
    }
    else{
        error_log("Error updating redirect : " . mysqli_error($conn));
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
$nodeHost = $node['nodeId'];
$sql_user = $node['sql_user'];
$nodeSQLUser =  $sql_user ? $sql_user : $node['NodeName'];
$nodeSQLPass = $node['sql_key'];
$panelDB = $panel['panelId'];
$NodeConn = new mysqli($nodeHost, $nodeSQLUser, $nodeSQLPass, $panelDB);

if(!$NodeConn){
    ErrorHandler::serverError();
}

$session = DB::getSession($NodeConn,$sessionID,false);

if(!$session){
    error_log("Error saving session: " . mysqli_error($NodeConn));
    ErrorHandler::serverError();
}

if(count($session)==0){
    echo json_encode(array(
        "status"=>"ok",
        "session"=>null
    ));
}

$updated=DB::updateRedirect($NodeConn,$sessionID,$newRedirect,$setError);
if($updated){
    echo json_encode(array(
        "status"=>"ok",
        "Next_Redirect"=>$newRedirect
    ));
}
else{
    error_log("Error updating redirect : " . mysqli_error($NodeConn));
    ErrorHandler::serverError();
}
?>