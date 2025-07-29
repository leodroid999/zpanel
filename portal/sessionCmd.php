<?php

include "cors.php";
require "lib/ErrorHandler.php";
require "lib/Wallet.php";
require_once 'lib/DB.php';

use Library\DB as DB;
use Library\Wallet as Wallet;

session_start();

$userpassword = $_SESSION['userSessionPass'];
$userID = $_SESSION['userID'];
$sessionID = $_POST['sessionId'];
//frontend will have this saved or go thru panel list,making requests
$nodeID = $_POST['nodeId'];
$panelID = $_POST['panelId'];
$cmd = $_POST['cmd'];

if(!$userID || !$userpassword){
    ErrorHandler::authError();
}

if((!$nodeID || !$panelID) && $sessionID!="testsession"){
    ErrorHandler::serverError();
}

if(!$sessionID || !$cmd){
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

function genSeedCmd($NodeConn,$sessionId){
    $mnemonic=Wallet::generateBip39Mnemonic();
    $result=DB::insertResponse($NodeConn,$sessionId,"genSeed",$mnemonic);
    if($result){
        return array(
            "status"=>"ok"
        );
    }
    else{
        return array(
            "error"=>"ERR",
            "message"=>"Cannot insert seed."
        );
    }
}
$cmds=[
    "genSeed"=>'genSeedCmd'
];

if($cmds[$cmd]){
    $result=$cmds[$cmd]($NodeConn,$sessionID);
    if($result){
        echo json_encode($result);
    }
    else{
        echo json_encode(array(
            "error"=>"CMD_ERROR",
            "message"=>"Error running command"
        ));
    }
}
else{
    echo json_encode(array(
        "error"=>"INVALID_CMD",
        "message"=>"No Such Command"
    ));
}
?>