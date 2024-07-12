<?php
include "cors.php";
include 'lib/DB.php';

use Library\DB as DB;

$conn = DB::connect();

// Check connection
if (!$conn) {
    error_log("Connection failed: " . mysqli_connect_error());
}

session_start();
$userId = $_SESSION['userID'];
$today = Date('Y-m-d H:i:s');

$query = "UPDATE users SET remember_token='', remember_expires='$today' where userId='$userId'";


if (mysqli_query($conn, $query)) {

    session_destroy();

    $SUCCESS = json_encode(
        array(
            'status' => "ok",
            'message' => "Logout sucessfull",
        )
    );
} else
    $SUCCESS = json_encode(
        array(
            'status' => "no",
            'message' => "There are some errors occurred in logout"
        )
    );


echo $SUCCESS;
