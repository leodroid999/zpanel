<?php

include "cors.php";
require "lib/ErrorHandler.php";
require_once 'lib/DB.php';

use Library\DB as DB;

session_start();

$userID = $_SESSION['userID'];

if (!isset($userID)) {
    ErrorHandler::authError();
}

// Connect to the database
$conn = DB::connect();

// Check connection
if (!$conn) {
    error_log("Connection failed: " . mysqli_connect_error());
    ErrorHandler::serverError();
}

$user = DB::getUser($conn, $userID, true);
if (!$user) {
    error_log("Error loading user data: " . mysqli_error($conn));
    ErrorHandler::authError();
}

$user = $user[0];

$enableLogs = $_POST['enableLogs'];

if ($enableLogs == 'true')
    $enableLogs = 1;
else
    $enableLogs = 0;

$result = DB::saveUserEnableLogs($conn, $userID, $enableLogs);

if ($result) {
    echo json_encode(array(
        "status" => "ok",
        "message" => "Saved sucessfully.",
    ));
} else {
    ErrorHandler::serverError();
}
