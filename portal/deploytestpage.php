<?php
    require_once "lib/FS.php";
    require_once "lib/DB.php";
    require_once "lib/Util.php";
    require_once "lib/CMD.php";

    require_once "lib/BlueprintCfg.php";
    #require_once "panel_files/db.php";
    require_once "editor/test_files/createPhpFile.php";
    require_once "editor/test_files/generateStartPage.php";

    require_once "lib/BlueprintCfg.php";
    include "cors.php";
    include "lib/authcheck.php";


    $blueprint = $_POST['blueprint'];
    
    use Library\DB as DB;
   
    function DeployTestPage($blueprint){
        $rand = mt_rand(1, 99999999);
        
        $conn=DB::connect();
        $userID = $_SESSION['userID'];
        $targetDir="/var/www/html/portal/editor/test/$userID/$blueprint";

        Util::output_line("Target Dir: $targetDir");

        $baseSrc="/data/fixedbackend.zip";
        $baseTarget="$targetDir/base.zip";

        Util::output_line("creating test dir for panel");

        if (!file_exists($targetDir)) {
            $result=mkdir($targetDir, 0777, true);
            if($result){
                Util::output_line("OK");
            }
            else{
                return  Util::output_line("Failed");
            }
    
        }

        Util::output_line("Copying base zip");

        $result = copy($baseSrc, $baseTarget);
        if($result){
            Util::output_line("OK");
        }
        else{
            return  Util::output_line("Failed");
        }

        $pageSrc="/data/$blueprint.zip";
        $pageTarget="$targetDir/$blueprint.zip";

        Util::output_line("Copying page zip");

        $result = copy($pageSrc, $pageTarget);
        if($result){
            Util::output_line("OK");
        }
        else{
            return  Util::output_line("Failed");
        }

        Util::output_line("Extracting base");
        $zip = new ZipArchive;
        $res = $zip->open($baseTarget);
        if ($res === TRUE) {
            $zip->extractTo($targetDir);
            $zip->close();
            Util::output_line("OK");
        } else {
            return Util::output_line("Failed");
        }

        Util::output_line("Extracting page");
        $zip = new ZipArchive;
        $res = $zip->open($pageTarget);
        if ($res === TRUE) {
            $zip->extractTo($targetDir);
            $zip->close();
            Util::output_line("OK");
        } else {
            return Util::output_line("Failed");
        }

        Util::output_line("Generating php files");
        $siteCfg = getBlueprintCfg($conn,$blueprint);

        if(!$siteCfg["files"]){
            Util::output_line("No files defined for site");
            return;
        }
        $files  = $siteCfg["files"];

        if(!$siteCfg["params"]){
            Util::output_line("No tokens defined for site");
            return;
        }
        $params  = $siteCfg["params"];

        foreach($files as $file){
            $phpfilename=substr_replace($file , 'php', strrpos($file , '.') +1);
            Util::output_line("Generating $phpfilename");
            $fileTokens =  $params[$file];
            if(!$fileTokens || count($fileTokens) == 0 ){
                Util::output_line("No tokens defined for file. Skipping!.");
                continue;
            }
            else{
                $tokenName = null;
                foreach($fileTokens as $token){
                    if($token["tokenButtonType"] == "standard"){
                        $tokenName = $token["tokenName"];
                        Util::output_line("main token: $tokenName");
                        break;
                    }           
                }
                if(!$tokenName){
                    Util::output_line("No standard tokens defined for file. Skipping!.");
                    continue;
                }
                $phpFileContents = generate_php_file($tokenName,$blueprint);
                $phpFileOutName="$targetDir/$phpfilename";

                $outfile = fopen($phpFileOutName,"w");
                if(!$outfile){
                    Util::output_line("Error creating file");
                    break;                    
                }
                $result = fwrite($outfile,$phpFileContents);
                if(!$result){
                    Util::output_line("Error creating file");
                    break;      
                }
                Util::output_line("OK");
            }
        }

        Util::output_line("Generating index");
        if(!$siteCfg["siteParams"]){
            Util::output_line("No start page defined.");
        }
        $siteParams = $siteCfg["siteParams"][0];
        $startPage = $siteParams["startpage"];
        $startPagePhp=substr_replace($startPage , 'php', strrpos($startPage , '.') +1);
        Util::output_line("Start page: $startPagePhp");

        $indexFileContents = generate_start_page($startPagePhp);
        $indexFileOutName="$targetDir/index.php";
        $outfile = fopen($indexFileOutName,"w");
        if(!$outfile){
            Util::output_line("Error creating file");
            return;                   
        }
        $result = fwrite($outfile, $indexFileContents);
        if(!$result){
            Util::output_line("Error creating file");
            return;      
        }
        Util::output_line("OK");
        Util::output_line("Done");
    }

   DeployTestPage($blueprint);
?>
