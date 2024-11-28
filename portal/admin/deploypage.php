<?php
    require_once "lib/FS.php";
    require_once "lib/DB.php";
    require_once "lib/Util.php";
    require_once "lib/CMD.php";

    require_once "panel_files/db.php";
    require_once "panel_files/generatePageFile.php";
    require_once "panel_files/generateStartPage.php";
    require_once "panel_files/_pageCfg.php";

    include "lib/cors.php";
    include "lib/authcheck.php";

    if($user['user_type']!='admin'){
        return ErrorHandler::authError();
    }

    $nodeId = $_POST['nodeId'];
    $panelId = $_POST['panelId'];
    $pageId = $_POST['pageId'];
    $folderName = $_POST['folderName'];
   
    function DeployPage($nodeId, $panelId, $pageId,$folderName){

        Util::output_line("Deploying page $pageId for $panelId @ node $nodeId");
        $conn=DB::connect();
        if(!$conn){
            Util::output_line("DB Connection error");
            return;
        }
        $node=DB::getNode($conn,$nodeId);
        if(!$node or count($node)==0){
            Util::output_line("Couldnt load node info.");
            return;
        }
        $node=$node[0];
        $page=DB::getPage($conn,$pageId);
        if(!$page or count($page)==0){
            Util::output_line("Couldnt load page info.");
            return;
        }
        $page=$page[0];
        var_dump($folderName);
        if(!isset($folderName) || $folderName == "null"){
            $folderName="";
        }
        $baseTargetDir = "/var/www/html/$panelId";
        $targetDir="/var/www/html/$panelId/$folderName";
        $targetDir=str_replace("//","/",$targetDir);

        Util::output_line("Generating page config file");
        $userID = $_SESSION['userID'];
        if(!isset($userID)){
            ErrorHandler::authError();
        }
        $user = DB::getUser($conn, $userID);
        if($user){
            $user=$user[0];
        }
        else{
            Util::output_line("Error getting account info");
        }

        $content=generate_page_file($pageId);
        if(!$content){
            Util::output_line("Error generating page config file");
            return;
        }
        $pageCfgFileSrc=FS::create_tempfile($content,"page");
        if(!$pageCfgFileSrc){
            Util::output_line("Error generating page config file");
            return;
        }
        $pageCfgFileTarget="$baseTargetDir/page.php";

        $siteCfg=getBluePrintCfg($conn,$pageId);
        $files  = $siteCfg["files"];
        $params  = $siteCfg["params"];

        Util::output_line("Connecting to server");
        $ssh_conn=CMD::connect_ssh($nodeId,$node['server_user'],$node["server_password"]);
        if(!$ssh_conn){
            return;
        }
        Util::output_line("Connected to server");
        Util::output_line("Uploading files");
        $sftp=ssh2_sftp($ssh_conn);
        $targetdir_exists=file_exists('ssh2.sftp://' . $sftp . $targetDir);
        if(!$targetdir_exists){
            $dir=ssh2_sftp_mkdir($sftp,$targetDir);
            if(!$dir){
                Util::output_line("Cannot create folder");
                return;
            }
        }
        $baseSrc="/data/newfixedbe.zip";
        $baseTarget="$baseTargetDir/base.zip";
        $pageFilename=$page['assetDir'];
        $pageSrc="/data/$pageFilename.zip";
        $pageTarget="$targetDir/$pageFilename.zip";
        Util::output_line("uploading $baseTarget");
        $uploadDone=FS::upload_file($sftp,$baseSrc,$baseTarget);
        if(!$uploadDone){
            Util::output_line("File upload failed");
            return;
        }
        Util::output_line("uploading $pageTarget");
        $uploadDone=FS::upload_file($sftp,$pageSrc,$pageTarget);
        if(!$uploadDone){
            Util::output_line("File upload failed");
            return;
        }

        Util::output_line("uploading $pageCfgFileTarget");
        $uploadDone=FS::upload_file($sftp,$pageCfgFileSrc,$pageCfgFileTarget);
        if(!$uploadDone){
            Util::output_line("File upload failed");
            return;
        }

        Util::output_line("extracting..");
        CMD::run_cmd($ssh_conn,"apt install unzip");
        Util::output_line("extracting base files..");
        $extractBaseCmd="unzip -o $baseTarget -d $baseTargetDir";
        CMD::run_cmd($ssh_conn,"$extractBaseCmd");

        Util::output_line("extracting panel files..");
        $extractPageCmd="unzip -o $pageTarget -d $targetDir";
        CMD::run_cmd($ssh_conn,"$extractPageCmd");

        CMD::run_cmd($ssh_conn,"rm $baseTarget");
        CMD::run_cmd($ssh_conn,"rm $pageTarget");

        Util::output_line("Done.");
   }

   DeployPage($nodeId,$panelId,$pageId,$folderName);
?>
