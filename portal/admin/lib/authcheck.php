<?php
session_start();
$userID = $_SESSION['userID'];
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

$user = DB::getUser($conn, $userID);
if(!$user){
    error_log("Error loading user data: " . mysqli_error());
    ErrorHandler::authError();
}
$user=$user[0];
?>