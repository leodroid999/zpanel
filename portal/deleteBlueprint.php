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

$blueprint_name = $_POST['blueprint_name'];

$result = DB::getBlueprint($conn, $blueprint_name);

if ($result) {
    unlink('../../../../data/' . $result[0]["assetDir"]);
    $status = DB::deleteBlueprint($conn, $blueprint_name);

    echo json_encode(
        array(
            "status" => "ok",
            'blueprint' => $status,
            "message" => "Blueprint deleted successfully.",
        )
    );
} else {
    echo json_encode(
        array(
            "status" => "error",
            "message" => "The blueprint is not existing"
        )
    );
}
?>