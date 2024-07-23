<?php
include "cors.php";
require "lib/ErrorHandler.php";
require_once 'lib/DB.php';

use Library\DB as DB;

// change this to server side sessions saved in sql
session_start();
// $userID = $_SESSION['userID'];

// if(!$userID){
//     ErrorHandler::authError();
// }

// Connect to the database
$conn = DB::connect();

// Check connection
if (!$conn) {
    error_log("Connection failed: " . mysqli_connect_error());
    ErrorHandler::serverError();
}

$users=DB::getAllUsers($conn, '');
if(!$users){
    error_log("Error loading user data: " . mysqli_error($conn));
    ErrorHandler::authError();
}

$responseData = new stdClass();
$responseData->data = $users;

echo json_encode(
    $responseData
)
?>