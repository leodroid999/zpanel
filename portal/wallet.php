<?php

include "cors.php";
require 'vendor/autoload.php';
require "lib/ErrorHandler.php";
session_start();

use Library\Wallet as Wallet;
use Library\Api as Api;
use Library\DB as DB;

$userpassword = $_SESSION['userSessionPass'];
$userID = $_SESSION['userID'];

if(!isset($userID) || !isset($userpassword)){
    ErrorHandler::authError();
}

// Connect to the database
$db = DB::connect();

// Check connection
if (!$db) {
    error_log("Connection failed: " . mysqli_connect_error());
    ErrorHandler::serverError();
}

// change this to server side sessions saved in sql

// Query to retrieve the hashed password
$sql = "SELECT password FROM users WHERE userID = '$userID' LIMIT 1";
$result = $db->query($sql);

// Check if the query was successful
if ($result && $result->num_rows > 0) {
    // Fetch the first row
    $row = $result->fetch_assoc();
    // Echo the hashed password
    $hashed_password = $row['password'];

} else {
    echo "No results found.";
}

if (password_verify($userpassword, $hashed_password)) {
  // echo "Welcome! Session Length & HASH integrity valid";
  // echo $hashed_password;
  // echo $userpassword;
} else {
   //echo "Password does not match.";
   session_destroy();
   ErrorHandler::authError();
   exit;
}


$user = DB::getUser($db, $userID);
if ($user) {
  $user=$user[0];
  $btcWallet=DB::getUserBTCAddress($db,$userID);
  if(!$btcWallet){
    ErrorHandler::serverError();
  }
  if($btcWallet=="NO_ADDR"){
      $btcCreated=DB::createBTCAdresssForUser($db,$userID);
      if($btcCreated){
        $btcWallet=DB::getUserBTCAddress($db,$userID);
      }
      else{
        ErrorHandler::serverError();
      }
  }
  $ethWallet=DB::getUserETHAddress($db,$userID);
  if(!$ethWallet){
    ErrorHandler::serverError();
  }
  if($ethWallet=="NO_ADDR"){
    $ethCreated=DB::createETHAdresssForUser($db,$userID);
    if($ethCreated){
      $ethWallet=DB::getUserETHAddresses($db,$userID);
    }
    else{
      ErrorHandler::serverError();
    }
  }
  $walletIndex = $btcWallet[0]["wallet_index"];
  $ethWalletIndex = $ethWallet[0]["eth_wallet_index"];
  $user["wallet_index"]=$walletIndex;
  $user["eth_wallet_index"]=$ethWalletIndex;
  $walletAddress = Wallet::getWalletByIndex($walletIndex);
  $ethWalletAddress = Wallet::getEthWalletByIndex($ethWalletIndex);
  try{
    $btcWalletStatus=Wallet::checkUserBTC($db,$user);
    $ethWalletStatus=Wallet::checkUserETH($db,$user);
  }
  catch(Exception $e){
    ErrorHandler::serverError();
  }
  $user = DB::getUser($db, $userID)[0];
}
mysqli_close($db);
echo json_encode(array(
  "status"=>"ok", 
  "data"=>array(
    "balance"=>$user['balance'],
    "wallets"=>array(
      "btc"=>array(
        "address"=>$walletAddress,
        "tx_history"=>$btcWalletStatus["tx_history"],
        "pending_tx"=>isset($btcWalletStatus["unconfirmed_tx"]) ? $btcWalletStatus["unconfirmed_tx"] :  null
      ),
      "eth"=>array(
        "address"=>$ethWalletAddress,
        "tx_history"=>$ethWalletStatus["tx_history"],
        "pending_tx"=>isset($ethWalletStatus["unconfirmed_tx"]) ? $ethWalletStatus["unconfirmed_tx"] :  null
      )
    )
  )
))
?>