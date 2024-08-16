<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
error_reporting(E_ALL);
require_once "lib/Util.php";
require_once "lib/CMD.php";
require_once "lib/DB.php";
require_once "lib/ErrorHandler.php";
include "lib/cors.php";
include "lib/authcheck.php";

if($user['user_type']!='admin'){
    return ErrorHandler::authError();
}
$nodeId = $_POST['nodeId'];
$panelId = $_POST['panelId'];


function DeletePanel($nodeId, $panelId)
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
    Util::output_line("Deleting panel $panelId @ node $nodeId");
    Util::output_line("Connecting to server");
    $ssh_conn=CMD::connect_ssh($nodeId,$node['server_user'],$node["server_password"]);
    if(!$ssh_conn){
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
    Util::output_line("Deleting panel from db");
    $removePanelResult = DB::removePanel($conn,$nodeId, $panelId);
    if (!$removePanelResult) {
        Util::output_line("Failed to delete panel");
        return;
    } 
    Util::output_line("done");
}

DeletePanel($nodeId, $panelId)
?>
