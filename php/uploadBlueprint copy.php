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

$cfg = $_FILES['cfg'];
$zip = $_FILES['zip'];
$engine = $_POST['engine'];

$result = DB::insertBlueprint($conn, substr($cfg['name'], 0, strrpos($cfg['name'], ".")), $zip['name'], $engine);
error_log(var_export($result, true));

if ($result) {
    move_uploaded_file($_FILES['cfg']['tmp_name'], '../../../../data/' . $_FILES['cfg']['name']);
    move_uploaded_file($_FILES['zip']['tmp_name'], '../../../../data/' . $_FILES['zip']['name']);
    $blueprints = DB::getBlueprints($conn);
    echo json_encode(
        array(
            "status" => "ok",
            "message" => "Blueprint saved successfully.",
            "blueprints" => $blueprints
        )
    );
} else {
    echo json_encode(
        array(
            "status" => "error",
            "message" => "Error saving blueprint"
        )
    );
}
?>