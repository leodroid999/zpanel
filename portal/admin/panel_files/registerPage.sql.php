<?php
    require_once "../lib/BlueprintCfg.php";
    function generate_register_page($conn,$rand,$pageId,$panelId){
        $pageIdRnd = $pageId . $rand;
        $siteParamList=[
            'pageEngine', 'startpage', 'version',
             'antibot_passive', 'antibot_active', 'mobile_only', 'errorMsg1', 'errorMsg2', 'errorMsg3', 'endUrl', 'dataName1', 'dataName2', 'dataName3', 'dataName4', 'dataName5'
        ];
        $siteConfig=getBluePrintCfg($conn,$pageId);
        if(!$siteConfig){
            return false;
        }
        $siteParams=$siteConfig['siteParams'];
        if(!$siteParams){
            return false;
        }
        $siteParams=$siteParams[0];
        $paramValues=[];
        $paramComputed=[];
        foreach($siteParamList as $paramName){
            if(array_key_exists($paramName,$siteParams)){
                array_push($paramValues,"'$siteParams[$paramName]'");
            }
            else{
                array_push($paramValues,"NULL");
            }
        }
        $paramStr=implode(", ",$paramValues);
        $content = <<<EOF
        INSERT INTO pages (pageID, panelID, pageName, pageEngine, startpage, version, antibot_passive, mobile_only, antibot_active, errorMsg1, errorMsg2, errorMsg3, endUrl, dataName1, dataName2, dataName3, dataName4, dataName5) VALUES
        ('$pageIdRnd', '$panelId', '$pageId', $paramStr);\n
        EOF;
        return $content;
    }
?>