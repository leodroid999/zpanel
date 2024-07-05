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

$engine = $_POST['engine'];
$pageName = $_POST['pageName'];

// Create PageName Folder in blueprints
mkdir("./blueprints/" . $pageName . "/");
mkdir("./blueprints/" . $pageName . "/pages/");

error_log("upload Blueprint : " . getcwd());


$result = DB::insertBlueprint($conn, $engine, $pageName, $user[0]["username"]);
error_log(var_export($result, true));

if ($result) {
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