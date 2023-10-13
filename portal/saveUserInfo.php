<?php

include "cors.php";
require "lib/ErrorHandler.php";
require_once 'lib/DB.php';

use Library\DB as DB;

session_start();

$userID = $_SESSION['userID'];
$currentPassword = $_POST['currentPassword'];

if(!isset($userID) || !isset($currentPassword)){
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
        "message"=>"invalid password"
    ));
    return;
}

$newUsername = $user['username'];
if(isset($_POST['username'])){
    $newUsername=$_POST['username'];
}
$newPassword = $user['password'];
if(isset($_POST['newPassword'])){
    $newPassword=password_hash($_POST['newPassword'],PASSWORD_DEFAULT);
}

$chatID = $user['chatID'];
error_log($chatID);
if(isset($_POST['chatID'])){
    $chatID=$_POST['chatID'];
}

$webNotifs = $user["webNotifs"];
if(isset($_POST['webNotifs'])){
    if($_POST['webNotifs'] == "true")
        $webNotifs = 1;
    else    
        $webNotifs = 0;
}


$result= DB::saveUserInfo($conn,$userID,$newUsername,$newPassword,$chatID, $webNotifs);

if($result){
    echo json_encode(array(
        "status"=>"ok",
        "message"=>"Saved sucessfully.",
    ));
}
else{
    ErrorHandler::serverError();
}
?>