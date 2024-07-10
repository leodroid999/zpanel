<?php
    const defaultConfig=[
        'files'=>[
           'home.html',
           'check.html'
        ],
        'params'=>[
            'home.html'=>[
                'tokenName'=>'sms'
            ],
            'check.html'=>[
                'tokenName'=>'sms'
            ]
        ],
        'siteParams'=>[
            'pageEngine'=>'v2',
            'version'=>'v1',
            'errorMsg1'=>'', 
            'errorMsg2'=>'', 
            'errorMsg3'=>'',
            'startpage'=>'home.php'
        ]
    ];
    function getPageCfg($pageId){
        $pagesConfig=[];
        if(file_exists("/data/$pageId.cfg")){
            require "/data/$pageId.cfg";
            $pagesConfig[$pageId]=$pageConfig;
        }
        else{
            $pagesConfig[$pageId]=defaultConfig;
        }
        return $pagesConfig;
    }
?>