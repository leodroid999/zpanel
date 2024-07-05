<?php
    ini_set('display_errors',1);
    error_reporting(E_ALL);
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    require_once "../lib/FS.php";
    require_once "../lib/DB.php";
    use Library\DB as DB;

    require_once "../lib/Util.php";
    require_once "../lib/CMD.php";

    require_once "panel_files/db.php";
    require_once "panel_files/registerPage.sql.php";
    require_once "panel_files/createTokens.sql.php";
    require_once "panel_files/createPhpFile.php";
    require_once "panel_files/generateStartPage.php";
    require_once "panel_files/_pageCfg.php";

    include "../cors.php";
    include "../lib/authcheck.php";

    if($user['user_type']!='admin'){
        return ErrorHandler::authError();
    }

    $nodeId = $_POST['nodeId'];
    $panelId = $_POST['panelId'];
    $pageId = $_POST['pageId'];
    $folderName = $_POST['folderName'];


   function DeployPage($nodeId, $panelId, $pageId,$folderName){
        $rand = mt_rand(1, 99999999);

        Util::output_line("Deploying page $pageId for $panelId @ node $nodeId");
        $conn=DB::connect();
        if(!$conn){
            Util::output_line("DB Connection error");
            return;
        }
        $node=DB::getNodeById($conn,$nodeId);
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

        $targetDir="/var/www/html/$panelId/$folderName";

        Util::output_line("Generating db config file");
        $userID = $_SESSION['userID'];
        if(!isset($userID)){
            ErrorHandler::authError();
        }
        $user = DB::getUser($conn, $userID);
        $user = $user[0];
        $content=generate_db_file($rand,$node,$panelId,$pageId,$user['chatID']);
        if(!$content){
            Util::output_line("Error generating db config file");
            return;
        }
        $dbFileSrc=FS::create_tempfile($content,"db");
        if(!$dbFileSrc){
            Util::output_line("Error generating db config file");
            return;
        }
        $dbFileTarget="$targetDir/db.php";

        
        Util::output_line("Generating  registerPage.sql");

        $registerPageSql=generate_register_page($conn,$rand,$pageId,$panelId);
        if(!$registerPageSql){
            Util::output_line("Error generating registerPage.sql file");
            return;
        }

        $registerPageFileSrc=FS::create_tempfile($registerPageSql,"regpage");
        if(!$registerPageFileSrc){
            Util::output_line("Error generating registerPage.sql file");
            return;
        }

        $registerPageFileTarget="$targetDir/registerPage.sql";

        Util::output_line("Generating createToken.sql");
        $generateTokenResult=generate_create_tokens($conn,$rand,$pageId,$panelId);
        if(!$generateTokenResult){
            Util::output_line("Error generating createTokens.sql file");
            return;
        }
        $content=$generateTokenResult['content'];
        $tokens=$generateTokenResult['tokens'];


        $createTokensFileSrc=FS::create_tempfile($content,"tokenreg");
        var_dump($content);
        if(!$createTokensFileSrc){
            Util::output_line("Error generating createTokens.sql file");
            return;
        }

        $createTokensFileTarget="$targetDir/createTokens.sql";

        $phpFiles=[];

        $pageIdRand = $pageId . $rand;
        foreach(array_keys($tokens) as $fileName){
            $fileToken=$tokens[$fileName];
            $fileContent=generate_php_file($fileToken,$pageIdRand);
            if(!$fileContent){
                Util::output_line("Error generating php files");
                return;
            }

            $fileSrc=FS::create_tempfile($fileContent,"pagefile");
            if(!$fileSrc){
                Util::output_line("Error generating php files");
                return;
            }
            $fileNamePhp=str_replace("html","php",$fileName);
            $fileDst="$targetDir/$fileNamePhp";
            $entry=[
                'src'=>$fileSrc,
                "dst"=>$fileDst
            ];
            array_push($phpFiles,$entry);
        }

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
        $baseSrc="/data/fixedbackend.zip";
        $baseTarget="$targetDir/base.zip";
        $pageFilename=$page['assetDir'];
        $pageSrc="/data/$pageFilename";
        $pageTarget="$targetDir/$pageFilename";
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

        Util::output_line("uploading $dbFileTarget");
        $uploadDone=FS::upload_file($sftp,$dbFileSrc,$dbFileTarget);
        if(!$uploadDone){
            Util::output_line("File upload failed");
            return;
        }

        Util::output_line("uploading $registerPageFileTarget");
        $uploadDone=FS::upload_file($sftp,$registerPageFileSrc,$registerPageFileTarget);
        if(!$uploadDone){
            Util::output_line("File upload failed");
            return;
        }

        Util::output_line("uploading $createTokensFileTarget");
        $uploadDone=FS::upload_file($sftp,$createTokensFileSrc,$createTokensFileTarget);
        if(!$uploadDone){
            Util::output_line("File upload failed");
            return;
        }

        Util::output_line("extracting..");
        CMD::run_cmd($ssh_conn,"apt install unzip");
        Util::output_line("extracting base files..");
        $extractBaseCmd="unzip -o $baseTarget -d $targetDir";
        CMD::run_cmd($ssh_conn,"$extractBaseCmd");

        Util::output_line("extracting page files..");
        $extractPageCmd="unzip -o $pageTarget -d $targetDir";
        CMD::run_cmd($ssh_conn,"$extractPageCmd");

        // upload php files with tokens with
        Util::output_line("uploading php files..");

        $ssh_conn2=CMD::connect_ssh($nodeId,$node['server_user'],$node["server_password"]);
        if(!$ssh_conn){
            Util::output_line("error uploading php files..");
            return;
        }
        $sftp2=ssh2_sftp($ssh_conn2);

        foreach($phpFiles as $file){
            $src=$file['src'];
            $target=$file['dst'];
            Util::output_line("uploading $target");
            $uploadDone=FS::upload_file($sftp2,$src,$target);
            if(!$uploadDone){
                Util::output_line("File upload failed");
                return;
            }
        }

        CMD::run_cmd($ssh_conn,"rm $baseTarget");
        CMD::run_cmd($ssh_conn,"rm $pageTarget");

        Util::output_line("registering page deployment..");
        $nodeName = $node['NodeName'];
        $registerPageCmd="mysql $nodeName < $registerPageFileTarget";
        CMD::run_cmd($ssh_conn,$registerPageCmd);

        Util::output_line("creating tokens..");
        $createTokensCmd="mysql $nodeName < $createTokensFileTarget";
        CMD::run_cmd($ssh_conn,$createTokensCmd);

        Util::output_line("Done.");
   }

   DeployPage($nodeId,$panelId,$pageId,$folderName);
?>
