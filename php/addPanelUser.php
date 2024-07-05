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
}
$access=null;
if(isset($_POST['access'])){
    $access=$_POST['access'];
}
else{
    echo json_encode(array(
        "status"=>"error",
        "message"=>"missing access level"
    ));
}


if(isset($_POST['username'])){
    $addedUsername=$_POST['username'];
}
else{
    echo json_encode(array(
        "status"=>"error",
        "message"=>"missing user name"
    ));
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
$addedUser = $addedUser[0];
$addedUserId = $addedUser["userId"];
$result = DB::addPanelUser($conn,$panelId,$addedUserId,$access);
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
        "message"=>"Error adding user to panel.",
    ));
}
?>