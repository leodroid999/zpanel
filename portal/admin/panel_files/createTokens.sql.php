<?php
    require_once "../lib/BlueprintCfg.php";

    function randomStr($length = 16) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characters_length = strlen($characters);
        $random_string = '';
        for ($i = 0; $i < $length; $i++) {
            $random_string .= $characters[rand(0, $characters_length - 1)];
        }
        return $random_string;
    }
    function add_file($content,$pageIdRnd,$file,$paramStr,&$tokenList){
        $rowStartContent = 
        "INSERT INTO tokensetup (tokenID, pageID, add_token, url_cosmetic, exception_antibot, ".
            "tokenButtonName, tokenButtonType, isMainRow, SendTokenWithError, tokenName, wait_lag, ".
            "enable_redirectpulse, a, b, c, x, y, z) VALUES";
        $randomStr=randomStr();
        $row = $rowStartContent . "('$randomStr', '$pageIdRnd', '$file', $paramStr);\n";
        $newContent=$content.$row;
        $tokenList[$file]=$randomStr;
        return $newContent;
    }
    function generate_create_tokens($conn,$rand,$pageId,$panelId){
        $tokenList=[];
        $paramList = [
            'url_cosmetic', 
            'exception_antibot', 'tokenButtonName', 'tokenButtonType', 'isMainRow', 'SendTokenWithError',
            'tokenName', 'wait_lag', 'enable_redirectpulse', 'a', 'b',
             'c', 'x', 'y', 'z'
        ];
        $pageIdRnd = $pageId . $rand;
        $siteConfig=getBluePrintCfg($conn,$pageId);
        if(!$siteConfig){
            return false;
        }
        $files=$siteConfig['files'];
        if(!$files){
            return false;
        }
        if(!array_key_exists('params',$siteConfig)){
            return false;   
        }
        $params=$siteConfig['params'];
        $content = "";
        foreach($files as $fileName){
            if(array_key_exists($fileName,$params)){
                $fileEntryList=$params[$fileName];
                foreach($fileEntryList as $fileCopy){
                    $paramValues=[];
                    foreach($paramList as $fileSetting){
                       
                        if(isset($fileCopy[$fileSetting])){
                            array_push($paramValues,"'$fileCopy[$fileSetting]'");
                        }
                        else{
                            array_push($paramValues,"NULL");
                        }
                    }
                    $paramStr=implode(", ",$paramValues);
                    $content=add_file($content,$pageIdRnd,$fileName,$paramStr,$tokenList);
                }
            }
        }
        $result=[
            'tokens'=>$tokenList,
            'content'=>$content
        ];
        return $result;
    }
?>
