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

$blueprint_name = $_POST["blueprint_name"];
$blueprintIndex = DB::getBlueprintIndex($conn, $blueprint_name);
$blueprintTokens = DB::getBlueprintTokens($conn, $blueprint_name);


$rootPath = realpath("./blueprints/" . $blueprint_name . "/pages/");

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


// Check file existing from index table but not tokens table
foreach($blueprintIndex as $index){
    $filename = $index["pagefile"];

    $existed = false;
    foreach($blueprintTokens as $token){
        if($token["pagefile"] == $filename){
            $existed = true;
            break;
        }
    }

    $existing_file = false;
    foreach ($files as $name => $file){
        if (!$file->isDir() && str_ends_with($name, $filename)){
            $existing_file = true;
            break;
        }
    }

    if($existed == false && $existing_file == false){
        DB::deleteBlueprintIndex($conn, $index["fileID"]);
    }
}


// Check files and insert into index table
foreach ($files as $name => $file)
{
    // Skip directories (they would be added automatically)
    if (!$file->isDir() && (str_ends_with($name, ".html") || str_ends_with($name, ".HTML")))
    {
        // Get real and relative path for current file
        $filePath = $file->getRealPath();
        $filename = basename($filePath);     

        $existed = false;
        foreach( $blueprintIndex as $index_record){
            if($index_record["pagefile"] == $filename){
                $existed = true;
                break;
            }
        }

        if(!$existed){
            DB::insertBlueprintIndex($conn, $blueprint_name, $filename);
        }
    }
}



// if ($result) {
    echo json_encode(
        array(
            "status" => "ok",
            "message" => "Blueprint reindexed successfully.",
        )
    );
// } else {
//     echo json_encode(
//         array(
//             "status" => "error",
//             "message" => "Error reindexing blueprint",
//             "error" => $result
//         )
//     );
// }
?>