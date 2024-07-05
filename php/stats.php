<?php
include "cors.php";
require "lib/ErrorHandler.php";
require_once 'lib/DB.php';

use Library\DB as DB;

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

session_start();

$userpassword = $_SESSION['userSessionPass'];
$userID = $_SESSION['userID'];
$nodeID = $_GET['nodeId'];
$panelID = $_GET['panelId'];

if(!$userID || !$userpassword){
    ErrorHandler::authError();
}

if(!$nodeID || !$panelID){
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
    ErrorHandler::serverError();
}
if($panel){
    $panel=$panel[0];
}

$nodeHost = $panel['nodeId'];
$sql_user = $panel['sql_user'];
$nodeSQLUser =  $sql_user ? $sql_user : $panel['NodeName'];
$nodeSQLPass = $panel['sql_key'];
$panelDB = $panel['panelId'];
// Connecting to the Node
$NodeConn = new mysqli($nodeHost, $nodeSQLUser, $nodeSQLPass, $panelDB);

if(!$NodeConn){
    ErrorHandler::serverError();
}

$locationStats  = DB::getLocationStats($NodeConn);
$OsStats  = DB::getOsStats($NodeConn);
$coords  = DB::getCoords($NodeConn);
$activity = DB::getActivity($conn);

echo json_encode(array(
    "status"=>"ok",
    "stats"=>array(
        "locations"=>$locationStats,
        "coords"=>$coords,
        "os"=>$OsStats,
        "activity"=>$activity
    )
));

?>