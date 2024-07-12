<?php
include "cors.php";
include 'lib/DB.php';

session_start();
$userId = $_SESSION['userID'];

$query = "UPDATE users SET remember_token='', remember_expires='' where userId='$userId'";
if (mysqli_query($conn, $query)) {
    session_destroy();
    $SUCCESS = json_encode(
        array(
            'status' => "ok",
            'message' => "Logout sucessfull"
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
