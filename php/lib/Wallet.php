<?php
namespace Library;

use Mdanter\Ecc\Curves;
use BitWasp\Bitcoin\Address\PayToPubKeyHashAddress;
use BitWasp\Bitcoin\Bitcoin;
use BitWasp\Bitcoin\Network\NetworkFactory;
use BitWasp\Bitcoin\Key\Factory\HierarchicalKeyFactory;
use Tatum\Sdk as Sdk;
use Library\DB as DB;

class Wallet {
    const TEST = false;
    public static function computeNewDeposits($receivedTxs, $chain) {
        $txValues = array_map(function($tx) use ($chain){
            $value = gmp_init($tx['valueUsd']);
            return $value;
        }, $receivedTxs);

        $totalValue = array_reduce($txValues, function($carry,$item) use ($chain){
            $carry = gmp_add($carry,$item);
                return $carry;
        },0);
        if($chain=="eth"){
            $totalValue=gmp_strval($totalValue);
        }
        return $totalValue;
    }
      
    public static function getLatestTxBlock($receivedTxs,$chain) {
        $blockHeights = array_map(function($tx) use($chain){
            return $chain === "btc" ? $tx["block_height"] : $tx["blockNumber"]; 
        }, $receivedTxs);
        rsort($blockHeights, SORT_NUMERIC);
        $latestBlock = $blockHeights[0];
        return $latestBlock;
    }
    
    public static function getWalletByIndex($index) {
        $fp = @fopen("/data/wallet_prv", 'r');
        $walletExtendedKey = FALSE;
        if ($fp) {
            $buffer = fgets($fp, 4096);
            if ($buffer) {
                $walletExtendedKey = $buffer;
            }
        }
        if(!$walletExtendedKey){
            return null;
        }
        if(self::TEST){
            Bitcoin::setNetwork(networkFactory::bitcoinTestnet());
        }
        $hdFactory = new HierarchicalKeyFactory();
        $master = $hdFactory->fromExtended($walletExtendedKey);
        $path="0/$index";
        $derivedKey = $master->derivePath($path);
        $derivedAddress = new PayToPubKeyHashAddress($derivedKey->getPublicKey()->getPubKeyHash());
        return $derivedAddress->getAddress();
    }

    public static function getEthWalletByIndex($index){
        $fp = @fopen("/data/eth_wallet_prv", 'r');
        $walletExtendedKey = FALSE;
        if ($fp) {
            $buffer = fgets($fp, 4096);
            if ($buffer) {
                $walletExtendedKey = $buffer;
            }
        }
        if(!$walletExtendedKey){
            return null;
        }
        $sdk = new Sdk();
        $address=$sdk
        ->testnet()
        ->local()->wallet()
        ->ethereum()
        ->generateAddressFromXpub($walletExtendedKey, $index)["address"];
        return $address;
    }
    public static function scanWallet($user,$address,$chain){
        $lastUpdateBlock = $chain ==="btc" ?
            $user["lastUpdateBlockBTC"] :
            $user["lastUpdateBlockETH"];

        $result=array(
            "unconfirmed_txs"=>[],
            "confirmed_txs"=>[],
            "newBalance"=>0,
            "latestUpdateBlock"=>$lastUpdateBlock
        );

        $newTxs = Api::fetchAddressTransactions($address,$lastUpdateBlock+1,$chain);
        if (array_key_exists("unconfirmed_txrefs",$newTxs)){
            $result["unconfirmed_txs"]=array_map(function($tx) use($address){
                $tx["address"]=$address;
                return $tx;
            },$newTxs["unconfirmed_txrefs"]);
        
        }
        if (array_key_exists("txrefs",$newTxs)){
            $result["confirmed_txs"]=array_map(function($tx) use($address){
                $tx["address"]=$address;
                return $tx;
            },$newTxs["txrefs"]);
        }
        $rates=null;
        $base=null;
        if($chain=='eth'){
            $rates=Api::getRates("ETH",15,24);
            $base='1000000000000000000';
        }
        else{
            $rates=Api::getRates("BTC",15,24);
            $base='100000000';
        }

        $rate=$rates[0]['open'];
        $rate=(int) round(($rate * 100), 0);
        $result['confirmed_txs']=array_map(function($tx) use($rate,$base){
            $tx['rate']=$rate;
            $valueUsd=gmp_div_q(
                gmp_mul($rate,$tx['value']),
                $base
            );
            $tx['valueUsd']=gmp_strval($valueUsd);
            return $tx;
        },$result["confirmed_txs"]);
        $confirmedTxs = $result["confirmed_txs"];
        if ($confirmedTxs) {
            $newDepositValue = self::computeNewDeposits($confirmedTxs,$chain);
            $newBalance = $newDepositValue;
            $result["latestUpdateBlock"] = self::getLatestTxBlock($confirmedTxs,$chain);
            $result["newBalance"]=$newBalance;
        }
        
        return $result;
    }

    public static function checkUserETH($db,$user){
        $walletIndex = $user["eth_wallet_index"];;
        $walletAddress = Wallet::getEthWalletByIndex($walletIndex);
        $ethWalletStatus = Wallet::scanWallet($user,$walletAddress,"eth");
        $newConfirmedTxs=$ethWalletStatus["confirmed_txs"];
        $ethTxHistory=DB::getEthTransactions($db,$user);
        if($newConfirmedTxs){
            $transactionSaveResult=DB::saveEthTransactions($db,$user,$walletAddress,$ethWalletStatus["confirmed_txs"]);
            if(!$transactionSaveResult){
                error_log("error saving eth txs");
                return false;
            }
            
            $updateBlockResult=DB::updateBalanceEthBlock($db,$user,$ethWalletStatus['newBalance'],$ethWalletStatus['latestUpdateBlock']);
            if(!$updateBlockResult){
                return FALSE;
            }

            foreach($ethWalletStatus["confirmed_txs"] as $tx){
                $tx=array(
                    "address"=>$tx['address'],
                    "valueEth"=>$tx['value'],
                    "hash"=>$tx['hash']
                );
                array_push($ethTxHistory,$tx);
            } 
        }
        $ethWalletStatus["tx_history"]=$ethTxHistory;
        return $ethWalletStatus;
    }

    public static function checkUserBTC($db,$user){
        $walletIndex = $user["wallet_index"];
        $walletAddress = Wallet::getWalletByIndex($walletIndex);
        $btcWalletStatus=Wallet::scanWallet($user,$walletAddress,"btc");
        $btcTxHistory=DB::getBtcTransactions($db,$user);
        $newConfirmedTxs=$btcWalletStatus["confirmed_txs"];
        if($newConfirmedTxs){
            $transactionSaveResult=DB::saveBtcTransactions($db,$user,$walletAddress,$btcWalletStatus["confirmed_txs"]);
            if(!$transactionSaveResult){
                error_log("error saving btc txs");
                return false;
            }
            
            $updateBlockResult=DB::updateBalanceBTCBlock($db,$user,$btcWalletStatus['newBalance'],$btcWalletStatus['latestUpdateBlock']);
            if(!$updateBlockResult){
                return FALSE;
            }

            foreach($btcWalletStatus["confirmed_txs"] as $tx){
                $tx=array(
                    "address"=>$tx['address'],
                    "valueBtc"=>$tx['value'],
                    "confirmedAt"=>rtrim($tx['confirmed'],'Z'),
                    "hash"=>$tx['tx_hash']
                );
                array_push($btcTxHistory,$tx);
            } 
        }
        $btcWalletStatus["tx_history"]=$btcTxHistory;
        return $btcWalletStatus;
    }
}
?>