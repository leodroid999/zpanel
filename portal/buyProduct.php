<?php
include "cors.php";
require "lib/ErrorHandler.php";
require_once 'lib/DB.php';

$names=[
    "Angelfish",
	"Anchovy",
	"AtlanticBluefinTuna",
	"Barracuda",
	"BlueWhale",
	"BottlenoseDolphin",
	"Clownfish",
	"Crab",
	"Cuttlefish",
	"Dugong",
	"EagleRay",
	"Flounder",
	"GreatWhiteShark",
	"GreenSeaTurtle",
	"HammerheadShark",
	"HermitCrab",
	"HumpbackWhale",
	"Icefish",
	"Orca",
	"Lobster",
	"MantaRay",
	"Manatee",
	"Marlin",
	"Narwhal",
	"Octopus",
	"Oyster",
	"Pufferfish",
	"Ray",
	"ReefShark",
	"Seahorse",
	"SeaLion",
	"SeaOtter",
	"SeaSlug",
	"SeaUrchin",
	"Seagull",
	"Seal",
	"Starfish",
	"SpermWhale",
	"Squid",
	"TigerShark",
	"Walrus",
	"WhaleShark",
	"ZebraShark",
];

use Library\DB as DB;

session_start();

$productID = $_POST['productID'];
$startTime = $_POST['startTime'];
$days = $_POST['days'];
$userID = $_SESSION['userID'];

if(!isset($userID)){
    ErrorHandler::authError();
}
if(!isset($productID)){
    ErrorHandler::serverError();
}
// Connect to the database
$conn = DB::connect();
// Check connection
if (!$conn) {
    error_log("Connection failed: " . mysqli_connect_error());
    ErrorHandler::serverError();
}

$user = DB::getUser($conn, $userID);
if(!$user){
    error_log("Error loading user data: " . mysqli_error($conn));
    ErrorHandler::authError();
}
$user=$user[0];
$product = DB::getProduct($conn,$productID);
if(!$product){
    error_log("Error loading product info: " . mysqli_error($conn));
    ErrorHandler::serverError();
}
$product=$product[0];
$price = $product['price'];
$isDownload = $product['isDownload'];
if(!$isDownload){
    if(!isset($days) || !isset($startTime)){
        ErrorHandler::serverError();
    }
    $dayAmount = intval($days);
    if(!$dayAmount){
        ErrorHandler::serverError();
    }
    $totalPriceCents=$dayAmount*$price*100;
    $balance=$user['balance'];
    if($totalPriceCents > $balance){
        echo json_encode(
            array(
                "status"=>"error",
                "error"=>"INSUFFICIENT_BALANCE",
                "message"=>"Insufficient funds, please make a deposit or adjust duration"
            )
        );
        exit();
    }
    $dateNow = new DateTimeImmutable('now',new DateTimeZone('UTC'));
    $tsNow = $dateNow->getTimestamp();
    $startTime = intval($startTime);
    if(!$startTime){
        ErrorHandler::serverError();
    }
    $startTime=intdiv($startTime,1000);
    if($tsNow>$startTime){
        $startTime=$tsNow;
    }
    $date=new DateTimeImmutable('now',new DateTimeZone('UTC'));
    $startDate = $date->setTimestamp($startTime);
    $endDate = $startDate->add(new DateInterval("P".$days."D"));
    $name=$array_rand($names);
}
?>