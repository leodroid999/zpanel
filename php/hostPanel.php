<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
error_reporting(E_ALL);

include "cors.php";
require_once "lib/ErrorHandler.php";
require_once "lib/DB.php";
require_once "lib/CMD.php";
require_once "lib/Util.php";

use Library\DB as DB;

session_start();

$host = $_POST['host'];
$server_username = $_POST['username'];
$password = $_POST['password'];
$panelId = $_POST['panelId'];
$nodeName = $_POST['nodeName'];
$userID = $_SESSION['userID'];

//Function HostPanel (Connects to a server and hosts with given parameters)
function HostPanel($nodeName,$panelId,$user,$host, $server_username, $password) {
    ///////////////// DEPLOY ////////////////////
    Util::output_line("Connecting to $host... ");

    $connection = ssh2_connect($host);
    if(!$connection){
        Util::output_line("Cannot connect to hostname/ip");
        return false;
    }
    echo("Authenticating...  ");

    $auth=ssh2_auth_password($connection,$server_username,$password);
    if(!$auth){
        Util::output_line("Wrong username/password");
        return false;

    }

    Util::output_line("Connected sucessfully ");
    Util::output_line("Saving $host as host for panel $panelId ");

    $conn=DB::connect();
    if(!$conn){
        Util::output_line("DB connection error");
        return;
    }

    $node = DB::getNode($conn,$nodeName);
    if(!$node){
        Util::output_line("Error loading node.");
        return;
    }
    $node=$node[0];
    $nodeId = $node["nodeId"];

    $addHostResult=DB::addHost($conn,$panelId,$host);
    if(!$addHostResult){
        Util::output_line("Error saving host for panel");
        return;
    }
    else{
        Util::output_line("Saved. ");
    }
    
    $cmd = 'wget -O - http://'.$panelId.".".$nodeId .'/setup.sh | bash -s - "'. $host .'"';
    CMD::run_cmd($connection,$cmd);
}

// Connect to the database
$conn = DB::connect();

// Check connection
if (!$conn) {
    error_log("Connection failed: " . mysqli_connect_error());
    ErrorHandler::serverError();
}

$user = DB::getUser($conn, $userID);
$user=DB::getUser($conn,$_SESSION['userID']);
if(!$user){
    error_log("Error loading user data: " . mysqli_error($conn));
    ErrorHandler::authError();
}

HostPanel($nodeName,$panelId,$user,$host,$server_username, $password);
?>
