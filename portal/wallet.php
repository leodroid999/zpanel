<?php

include "cors.php";
require 'vendor/autoload.php';
require "lib/ErrorHandler.php";
session_start();

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

use Library\Wallet as Wallet;
use Library\Api as Api;
use Library\DB as DB;

$userID = $_SESSION['userID'];

if(!isset($userID)){
    ErrorHandler::authError();
}

// Connect to the database
$db = DB::connect();

// Check connection
if (!$db) {
    error_log("Connection failed: " . mysqli_connect_error());
    ErrorHandler::serverError();
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
