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

$user = DB::getUser($conn, $userID);
if (!$user) {
  error_log("Error loading user data: " . mysqli_error($conn));
  ErrorHandler::authError();
}
$logs = DB::getBlueprintLogs($conn, $user[0]["username"]);
// if (!$blueprintTokens) {
//   error_log("Error loading blueprintTokens info: " . mysqli_error($conn));
//   ErrorHandler::serverError();
// }
echo json_encode(
  array(
    "status" => "ok",
    "message" => "Order fetched successfully.",
    "logs"=> $logs
  )
);
?>