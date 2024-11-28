<?php
    require_once "DB.php";
    use Library\DB as DB;
    function getBlueprintCfg($conn,$name){
        $files=[];
        $params=[];
        $blueprintTokens = DB::getBlueprintTokens($conn, $name);
        if(!$blueprintTokens){
            return null;
        }
        foreach($blueprintTokens as $token){
            $filename=$token["pagefile"];
            if(!in_array($filename,$files)){
                array_push($files,$filename);
                $params[$filename]=[];
            }
            unset($token["blueprint"]);
            array_push($params[$filename],$token);
        }
        $siteParams = DB::getBlueprint($conn, $name);
        $cfg=[
            "files"=>$files,
            "params"=>$params,   
            "siteParams"=>$siteParams        
        ];
        return $cfg;
    }
?>