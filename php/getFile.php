<?php
include "cors.php";
require "lib/ErrorHandler.php";
require_once 'lib/DB.php';

use Library\DB as DB;

session_start();

$userID = $_SESSION['userID'];
$filePath = $_POST['filePath'];


// Connect to the database
$conn = DB::connect();
// Check connection
if (!$conn) {
    error_log("Connection failed: " . mysqli_connect_error($conn));
    ErrorHandler::serverError();
}

$user = DB::getUser($conn, $userID);
if(!$user){
    error_log("Error loading user data: " . mysqli_error($conn));
    ErrorHandler::authError();
}

$fileContent = file_get_contents("./assets/products/" . $filePath);

echo json_encode(array(
    "status"=>"ok",
    "file"=>$fileContent
));

?>