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
require_once "$filesDir/server_config.php";
require_once "$filesDir/index.php";
require_once "$filesDir/config.php";
require_once "$filesDir/setup.sh.php";
require_once "$filesDir/setup.sql.php";
$nodeId = $_POST['nodeId'];
$panelId = $_POST['panelId'];
include "lib/cors.php";
include "lib/authcheck.php";

if($user['user_type']!='admin'){
    return ErrorHandler::authError();
}
$nodeId = $_POST['nodeId'];
$panelId = $_POST['panelId'];

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

function ReinstallPanel($filesDir,$nodeId, $panelId)
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
            "name"=>"index.php",
            "target"=>"/var/www/html/$panelId/index.php",
            "generate"=>'generate_index_file'
        ),
        array(
            "name"=>"config.php",
            "target"=>"/var/www/html/$panelId/config.php",
            "generate"=>'generate_config'
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
        )
    );
    $vars=array(
        "nodeId"=>$nodeId,
        "nodeName"=>$node['NodeName'],
        "panelId"=>$panelId,
        "reinstall"=>true
    );
    Util::output_line("Reinstalling panel $panelId @ node $nodeId");
    Util::output_line("Generating config");
    $generated_files=generate_files($filesDir,$files,$vars);
    if(!$generated_files){
        Util::output_line("error generating config");
        return;
    }
    Util::output_line("Connecting to server");
    $ssh_conn=CMD::connect_ssh($nodeId,$node['server_user'],$node["server_password"]);
    if(!$ssh_conn){
        output_line("Failed to connect to server");
        return;
    }
    Util::output_line("Connected to server");
    Util::output_line("Removing server config");
    CMD::run_cmd($ssh_conn,"rm /etc/apache2/sites-available/$panelId.conf");
    Util::output_line("Removing panel dir");
    CMD::run_cmd($ssh_conn,"rm -rf /var/www/html/$panelId");
    Util::output_line("Reloading server");
    CMD::run_cmd($ssh_conn,"service apache2 restart");
    Util::output_line("Deleting database");
    CMD::run_cmd($ssh_conn,"mysql -e 'DROP DATABASE $panelId'");
    Util::output_line("creating panel dir");
    $ssh_conn2=CMD::connect_ssh($nodeId,$node['server_user'],$node["server_password"]);
    if(!$ssh_conn2){
        output_line("Failed to connect to server");
        return;
    }
    $sftp=ssh2_sftp($ssh_conn2);
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
    $sftp=ssh2_sftp($ssh_conn2);
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
    Util::output_line("Creating database");
    CMD::run_cmd($ssh_conn,"mysql -e 'CREATE DATABASE $panelId'");
    Util::output_line("Creating database structure");
    CMD::run_cmd($ssh_conn,"mysql $panelId < /var/www/html/$panelId/setup.sql");
    Util::output_line("done");
}

ReinstallPanel($filesDir,$nodeId, $panelId)
?>
