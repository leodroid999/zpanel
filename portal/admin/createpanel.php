<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
error_reporting(E_ALL);
$filesDir="panel_files";
require_once "lib/FS.php";
require_once "lib/DB.php";
require_once "lib/Util.php";
require_once "lib/CMD.php";
require_once "lib/ErrorHandler.php";
require_once "$filesDir/server_config.php";
require_once "$filesDir/setup.sh.php";
require_once "$filesDir/setup.sql.php";
require_once "$filesDir/db.php";
require_once "$filesDir/config.php";
include "lib/cors.php";
include "lib/authcheck.php";
$nodeId = $_POST['nodeId'];
$panelId = $_POST['panelId'];
$userId = $_POST['userId'];

function generate_files($filesDir,$files,$vars){
    $result=[];
    foreach($files as $file){
        $content=NULL;
        if(!$file['generate']){
            $srcFile=$filesDir."/".$file["name"];
            $fp=fopen($srcFile,'r');
            if(!$fp){
                return false;
            }
            $content=fread($fp, filesize($srcFile));
            if(!$content){
                return false;
            }
        }
        else{
            $content=$file['generate']($vars);
        }
        if(!$content){
            $name=$file['name'];
            Util::output_line("error generating $name");
            return false;
        }
        $temp_file_path=FS::create_tempfile($content,$file["name"]);
        $entry=[
            "name"=>$file['name'],
            "src"=>$temp_file_path,
            "dest"=>$file['target']
        ];
        array_push($result,$entry);
    }
    return $result;
}

function CreatePanel($filesDir,$nodeId, $panelId, $userId)
{
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
    $files=array(
        array(
            "name"=>"server.conf",
            "target"=>"/etc/apache2/sites-available/$panelId.conf",
            "generate"=>'generate_server_config'
        ),
        array(
            "name"=>"setup.sh",
            "target"=>"/var/www/html/$panelId/setup.sh",
            "generate"=>'generate_setup_sh'
        ),
        array(
            "name"=>"setup.sql",
            "target"=>"/var/www/html/$panelId/setup.sql",
            "generate"=>'generate_setup_sql'
        ),
        array(
            "name"=>"config.php",
            "target"=>"/var/www/html/$panelId/config.php",
            "generate"=>'generate_config_php'
        )
    );

    $vars=array(
        "nodeName"=>$node['NodeName'],
        "nodeId"=>$nodeId,
        "panelId"=>$panelId
    );
    $user = DB::getUser($conn, $userId);
    if($user){
        $user=$user[0];
    }
    
    Util::output_line("Creating panel $panelId @ node $nodeId for userid=$userId");
    $addPanelResult = DB::addPanel($conn,$nodeId, $panelId, $userId);
    if (!$addPanelResult) {
        Util::output_line("Failed to save panel");
        return;
    } 

    Util::output_line("Created new panel. Setting up...");
    Util::output_line("Generating config");
    $generated_files=generate_files($filesDir,$files,$vars);
    if(!$generated_files){
        Util::output_line("error generating config");
        return;
    }
    $content=generate_db_file($node,$panelId,$userId);
    
    if(!$content){
        Util::output_line("Error generating db config file");
        return;
    }
    
    $dbFileSrc=FS::create_tempfile($content,"db");
    if(!$dbFileSrc){
        Util::output_line("Error generating db config file");
        return;
    }
    $dbFileTarget="/var/www/html/$panelId/db_cfg.php";
    
            
    Util::output_line("Connecting to server");
    $ssh_conn=CMD::connect_ssh($nodeId,$node['server_user'],$node["server_password"]);
    if(!$ssh_conn){
        return;
    }
    Util::output_line("Connected to server");
    Util::output_line("creating panel dir");
    $sftp=ssh2_sftp($ssh_conn);
    $panel_path = "/var/www/html/$panelId";
    $panel_path_exists=file_exists('ssh2.sftp://' . $sftp . $panel_path);
    if(!$panel_path_exists){
        $dir=ssh2_sftp_mkdir($sftp,$panel_path);
        if(!$dir){
            Util::output_line("Cannot create panel dir");
            return;
        }
    }
    Util::output_line("Uploading files");
    $sftp=ssh2_sftp($ssh_conn);
    Util::output_line("uploading $dbFileTarget");
    
    $uploadDone=FS::upload_file($sftp,$dbFileSrc,$dbFileTarget);
    if(!$uploadDone){
        Util::output_line("File upload failed");
        return;
    }
    $uploadDone=FS::upload_files($sftp,$generated_files);
    if(!$uploadDone){
        Util::output_line("File upload failed");
        return;
    }
    Util::output_line("Uploaded files!");
    Util::output_line("Enabling site");
    CMD::run_cmd($ssh_conn,"a2ensite $panelId");
    Util::output_line("Reloading server");
    CMD::run_cmd($ssh_conn,"service apache2 restart");
    Util::output_line("Dropping database");
    CMD::run_cmd($ssh_conn,"mysql -e 'DROP DATABASE IF EXISTS $panelId'");
    Util::output_line("Creating database");
    CMD::run_cmd($ssh_conn,"mysql -e 'CREATE DATABASE $panelId'");
    Util::output_line("Creating database structure");
    CMD::run_cmd($ssh_conn,"mysql $panelId < /var/www/html/$panelId/setup.sql");
    Util::output_line("done");
}

CreatePanel($filesDir,$nodeId, $panelId, $userId)
?>
