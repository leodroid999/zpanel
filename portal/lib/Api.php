<?php
  namespace Library;

  use Web3\Web3;
  use Web3\Providers\HttpProvider;
  use Web3\RequestManagers\HttpRequestManager;

  class Api{
    const TEST = false;
    const BTC_API_BASE_URL="https://api.blockcypher.com/v1/btc";
    const BTC_NET = self::TEST ? "test3":"main";
    const BTC_API_URL=self::BTC_API_BASE_URL . "/" . self::BTC_NET;
    const ETH_URL= self::TEST ?
    "https://api-goerli.etherscan.io/api":
    "https://api.etherscan.io/api";
      

    private static function fetch($url){
      $curl = curl_init();
      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

      $result = curl_exec($curl);
      if ($result=== false) {
          throw new Exception(curl_error($curl), curl_errno($curl));
      }
      return $result;
    }
    
    public static function fetchAddressTransactionsBTC($address,$fromBlock){
      $requestUrl = self::BTC_API_URL . "/addrs/$address?after=$fromBlock";
      $result= self::fetch($requestUrl);
      $data = json_decode($result, true);
      if(!$data){
        return FALSE;
      }
      $returnData = [];
      if(array_key_exists("txrefs",$data)){
        $returnData["txrefs"]=array_filter($data["txrefs"], function($tx) {
          return $tx["tx_input_n"] == -1;
        });
      }
      if(array_key_exists("unconfirmed_txrefs",$data)){
        $returnData["unconfirmed_txrefs"]=array_filter($data["unconfirmed_txrefs"], function($tx) {
          return $tx["tx_input_n"] == -1;
        });
      }
      return $returnData;
    }

    public static function fetchAddressTransactionsETH($address,$fromBlock){
      $returnData = [];
      $requestUrl = self::ETH_URL ."?module=account&action=txlist".
      "&address=$address".
      "&startblock=$fromBlock";
      $requestResult= self::fetch($requestUrl);
      $data = json_decode($requestResult, true);
      if(!$data){
        return FALSE;
      }
      if(!$data["status"]==="1"){
        return FALSE;
      }
      $result=$data["result"];
      $unconfirmed_txrefs=array_filter($result,function($tx) use ($address){
        return $tx["to"]===$address && $tx["confirmations"] == 0;
      });
      $confirmed_txrefs=array_filter($result,function($tx) use ($address){
        return $tx["to"]===$address && $tx["confirmations"] > 0;
      });
      $returnData["txrefs"]=$confirmed_txrefs;
      $returnData["unconfirmed_txrefs"]=$unconfirmed_txrefs;
      return $returnData;
    }

    public static function fetchAddressTransactions($address,$fromBlock,$chain){
      $requestUrl = "";
      if($chain=="btc"){
        return self::fetchAddressTransactionsBTC($address,$fromBlock);    
      }
      if($chain=="eth"){
        return self::fetchAddressTransactionsETH($address,$fromBlock);   
      }  
    }

    public static function getRates($currency,$periodlen,$limit){
      $requestUrl="https://min-api.cryptocompare.com/data/v2/histominute?".
        "fsym=$currency".
        "&tsym=USD".
        "&limit=$limit".
        "&aggregate=$periodlen".
        "&aggregatePredictableTimePeriods=true";
      
      $result= self::fetch($requestUrl);
      $data = json_decode($result, true);
      if(!$data){
        return FALSE;
      }

      if(!$data["Response"]=="Success"){
        return false;
      }

      return $data["Data"]['Data'];
    }
  }
?>