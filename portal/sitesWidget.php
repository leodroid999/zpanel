<?php
include "cors.php";
require "lib/ErrorHandler.php";
require_once 'lib/DB.php';

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

$user=$user[0];

$sites = getSiteWidgetList($conn,$user);
if($sites===null){
    error_log("Error loading site list: " . mysqli_error($conn));
    ErrorHandler::serverError();
}
if(count($sites)==0){
    echo json_encode(array(
        "status"=>"ok",
        "sites"=>[],
    ));
}
else{
    echo json_encode(array(
        "status"=>"ok",
        "sites"=>$sites,
    ));
}



function getSiteWidgetList($conn,$user){
    $query = $conn->prepare("SELECT * FROM hosts INNER JOIN panels ON hosts.panelID = panels.panelID where userId=? ORDER BY deploytime DESC LIMIT 4 ");
    $query->bind_param('i',$user['userId']);
    $query->execute();
    $queryresult=$query->get_result();
    if($queryresult){
        $result=$queryresult->fetch_all(MYSQLI_ASSOC);
        return $result;
    }
    return false;
}

?>