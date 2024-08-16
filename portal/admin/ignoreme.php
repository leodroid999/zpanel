<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "lib/DB.php";
require_once "lib/CMD.php";
require_once "lib/Util.php";

use phpseclib\Net\SSH2;

$server_domain = $_POST['server_domain'];
$server_username = $_POST['server_username'];
$password = $_POST['password'];
$panelId = $_POST['panelId'];
$nodeId = $_POST['nodeId'];

//Function HostPanel (Connects to a server and hosts with given parameters)
function HostPanel($nodeId, $panelId, $server_domain, $server_username, $password) {
    Util::output_line("Connecting to $server_domain... &#13;&#10;");

    $ssh = new SSH2($server_domain);
    if (!$ssh->login($server_username, $password)) {
        Util::output_line("Cannot connect to hostname/ip");
        return false;
    }
    echo("Authenticating...  &#13;&#10;");

    Util::output_line("Connected successfully &#13;&#10;");
    Util::output_line("Saving $server_domain as host for panel $panelId &#13;&#10;");

    $conn = DB::connect();
    if (!$conn) {
        Util::output_line("DB connection error");
        return;
    }

    $addHostResult = DB::addHost($conn, $panelId, $server_domain);
    if (!$addHostResult) {
        Util::output_line("Error saving host for panel");
        return;
    } else {
        Util::output_line("Saved. &#13;&#10;");
    }

    $cmd = 'wget -O - http://' . $panelId . "." . $nodeId . '/setup.sh | bash -s - "' . $server_domain . '"';
    CMD::run_cmd($ssh, $cmd);
}

HostPanel($nodeId, $panelId, $server_domain, $server_username, $password);
?>
