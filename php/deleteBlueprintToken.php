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

$blueprint_tokenId = $_POST['blueprint_tokenId'];


$result = DB::deleteBlueprintToken($conn, $blueprint_tokenId);

if ($result) {
    echo json_encode(
        array(
            "status" => "ok",
            "message" => "The token deleted successfully.",
        )
    );
} else {
    echo json_encode(
        array(
            "status" => "error",
            "message" => "Error occurred while deleting the token"
        )
    );
}
