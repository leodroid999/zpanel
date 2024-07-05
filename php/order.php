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
$order = DB::getOrder($conn, $userID);
if (!$order) {
  error_log("Error loading order info: " . mysqli_error($conn));
  ErrorHandler::serverError();
}
for ($i = 0; $i < count($order); $i++) {
  $order[$i]["file"] = file_get_contents("./assets/products/" . $order[$i]["filePath"]);
}
echo json_encode(
  array(
    "status" => "ok",
    "message" => "Order fetched successfully.",
    "order"=> $order
  )
);
?>