<?php
include "cors.php";
require "lib/ErrorHandler.php";
require_once 'lib/DB.php';

use Library\DB as DB;

session_start();

$userpassword = $_SESSION['userSessionPass'];
$userID = $_SESSION['userID'];

if(!$userID || !$userpassword){
    ErrorHandler::authError();
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

if(!$user["shortlinksPkg"]){
    ErrorHandler::authError();
}

$shortlinks = DB::getShortlinks($conn,$user);
if($shortlinks===null){
    error_log("Error loading shortlinks list: " . mysqli_error($conn));
    ErrorHandler::serverError();
}
if(count($shortlinks)==0){
    echo json_encode(array(
        "status"=>"ok",
        "shortlinks"=>[],
    ));
}
else{
    echo json_encode(array(
        "status"=>"ok",
        "shortlinks"=>$shortlinks,
    ));
}


?>