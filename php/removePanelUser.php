<?php

include "cors.php";
require "lib/ErrorHandler.php";
require_once 'lib/DB.php';

use Library\DB as DB;

session_start();

$userID = $_SESSION['userID'];
$currentPassword = $_POST['password'];

if(!isset($userID)){
    ErrorHandler::authError();
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

$user=$user[0];
if (!password_verify($currentPassword,$user['password'])) {
    echo json_encode(array(
        "status"=>"error",
        "message"=>"Error: invalid password"
    ));
    return;
}


if(isset($_POST['panelId'])){
    $panelId=$_POST['panelId'];
}
else{
    echo json_encode(array(
        "status"=>"error",
        "message"=>"missing panelid"
    ));
    return;
}


if(isset($_POST['username'])){
    $addedUsername=$_POST['username'];
}
else{
    echo json_encode(array(
        "status"=>"error",
        "message"=>"missing user name"
    ));
    return;
}

if($user['username']==$addedUsername){
    echo json_encode(array(
        "status"=>"error",
        "message"=>"Error: Cant add yourself to allowed user list"
    ));
    return;
}

$addedUser=DB::getUserId($conn,$addedUsername);
if($addedUser == null || count($addedUser) == 0){
    echo json_encode(array(
        "status"=>"error",
        "message"=>"Error: user does not exist"
    ));
    return;
}
// get panel this user is added to, no result = not added to this panel
$addedUser = $addedUser[0];
$panel=DB::getPanelAddedToById($conn,$addedUser,$panelId);
if($panel == null || count($panel) == 0){
    echo json_encode(array(
        "status"=>"error",
        "message"=>"Error: user is not added to panel"
    ));
    return;
}

$panel = $panel[0];
$addedUserId = $addedUser["userId"];
$result = DB::removePanelUser($conn,$panelId,$addedUserId);

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
        "message"=>"Error changing user access",
    ));
}
?>