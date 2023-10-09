<?php
include "cors.php";
require "lib/ErrorHandler.php";
require_once 'lib/DB.php';

use Library\DB as DB;

session_start();

$userpassword = $_SESSION['userSessionPass'];
$userID = $_SESSION['userID'];
$panelId = $_GET["panelId"];

if(!$userID || !$userpassword){
    ErrorHandler::authError();
}

if(!isset($panelId)){
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

$panel = DB::getPanel($conn,$user,$panelId);
if($panel===null){
    error_log("Error loading panel list: " . mysqli_error($conn));
    ErrorHandler::serverError();
    return;
}
if(count($panel)==0){
    ErrorHandler::serverError();
    return;
}
$users=DB::getPanelAccessList($conn,$panelId);
if($users===null){
    error_log("Error loading panel access list: " . mysqli_error($conn));
    ErrorHandler::serverError();
}
if(count($users)==0){
    echo json_encode(array(
        "status"=>"ok",
        "users"=>[],
    ));
}
else{
    echo json_encode(array(
        "status"=>"ok",
        "users"=>$users,
    ));
}

?>