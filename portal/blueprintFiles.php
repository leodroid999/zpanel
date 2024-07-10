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

$blueprint_name="";

if(isset( $_POST["blueprint_name"])){
  $blueprint_name = $_POST["blueprint_name"];
}
else{
  $blueprint_name = $_GET["blueprint_name"]; 
}

$path="./blueprints/$blueprint_name";
$rootPath = realpath($path);

// Create recursive directory iterator
/** @var SplFileInfo[] $files */
$files = new RecursiveIteratorIterator(
  new RecursiveDirectoryIterator($rootPath),
  RecursiveIteratorIterator::LEAVES_ONLY
);


//str_ends_with
if(!function_exists('str_ends_with')) {
  // echo 'str_ends_with doesn\'t exist<br/>';
  //str_ends_with(string $haystack, string $needle): bool
  function str_ends_with($haystack,$needle) {
      //str_starts_with(string $haystack, string $needle): bool

      $strlen_needle = mb_strlen($needle);
      if(mb_substr($haystack,-$strlen_needle,$strlen_needle)==$needle) {
          return true;
      }
      return false;
  }
}



$blueprintFiles = [];
foreach ($files as $name => $file) {
  // Skip directories (they would be added automatically)
  if (!$file->isDir() && (str_ends_with($name, ".html") || str_ends_with($name, ".HTML"))) {
    // Get real and relative path for current file
    $filePath = $file->getRealPath();
    array_push($blueprintFiles, basename($filePath));
  }
}



echo json_encode(
  array(
    "status" => "ok",
    "message" => "Order fetched successfully.",
    "blueprintFiles" => $blueprintFiles
  )
);
