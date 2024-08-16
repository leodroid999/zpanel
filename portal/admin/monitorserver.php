<?php 
$server_domain = $_POST['server_domain'];
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
error_reporting(E_ALL);

require_once "lib/DB.php";

function output_line($content){
  echo($content."<br>");
  ob_flush();
  flush();
}

function get_http_code($url) {
  $handle = curl_init($url);
  curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
  $response = curl_exec($handle);
  $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
  curl_close($handle);
  return $httpCode;         
}    

function MonitorServer($server_domain){
   $result=array(
    "ip"=>null,"status"=>"UNKNOWN"
   );
   $ip=gethostbyname($server_domain.".");
   if(!$ip or $ip==$server_domain."."){
     $result['status']="DOMAIN_DOWN";
     return $result;
   }
   else{
     $result['ip']=$ip;
   }
   $socket = fsockopen($server_domain, 80, $errno, $errstr, 30);
   if(!$socket){
    $result['status']="SERVER_DOWN";
    return $result;
   }
   $status = get_http_code("http://".$server_domain."/");
   // handle redirect to https
   if($status == 302 or $status == 301){
    $status = get_http_code("https://".$server_domain."/");
   }
   if($status == 200){
    $result['status']="ONLINE";
    return $result;
   }
   else{
    $result['status']="SITE_DOWN";
     return $result;
   }
}

output_line("Checking server status for $server_domain ");
$status=MonitorServer($server_domain);
switch ($status['status']) {
  case "DOMAIN_DOWN":
      output_line("The domain does not exist or does not have IP");
  break;
  case "SERVER_DOWN":
      output_line("Could not connect to the server");
  break;
  case "SITE_DOWN":
    output_line("Site is returning a error status ");
  break;
  case "ONLINE":
      output_line("Server is up ");
  break;
}
$conn=DB::connect();
$savedStatus=DB::saveHostStatus($conn,$server_domain,$status["status"],$status['ip']);
if($savedStatus){
  output_line("saved site status to db");
}
else{
  output_line("failed to save state to db");
}
?>