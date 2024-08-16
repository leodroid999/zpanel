<?php
    require_once "lib/FS.php";
    require_once "lib/DB.php";
    require_once "lib/Util.php";
    require_once "lib/CMD.php";

    require_once "lib/BlueprintCfg.php";
    #require_once "panel_files/db.php";
    #require_once "panel_files/registerPage.sql.php";
    require_once "editor/test_files/createPhpFile.php";
    #require_once "panel_files/generateStartPage.php";
    #require_once "panel_files/_pageCfg.php";

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
        Util::output_line(var_dump($siteCfg));
    }

   DeployTestPage($blueprint);
?>
