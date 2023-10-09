<?php
// Database connection parameters
include 'cors.php';
require 'lib/DB.php';

use Library\DB as DB;

$INTERNAL_ERROR=json_encode(
    array(
        'status'=>"error",
        'error'=>'SERVER_ERROR',
        'message'=>'There was a error, try again later'
    )
);


$USER_EXISTS=json_encode(
    array(
        'status'=>'error',
        'error'=>'USER_EXISTS',
        'message'=>'Username already exists'
    )
);

$USER_INVALID=json_encode(
    array(
        'status'=>'error',
        'error'=>'USER_INVALID',
        'message'=>'Username must be at least 3 characters long and contain only alphanumeric characters'
    )
);


// Connect to the database
$conn = DB::connect($host, $user, $password, $dbname);

// Check connection
if (!$conn) {
    error_log("Connection failed: " . mysqli_connect_error());
    echo $INTERNAL_ERROR;
    return;
}

// Get the user's information from the form
$username = mysqli_real_escape_string($conn, $_POST['username']);
$password = mysqli_real_escape_string($conn, $_POST['password']);
$telegram = mysqli_real_escape_string($conn, $_POST['telegram']);

// Generate a random 7-digit userID
$userID = mt_rand(1000000, 9999999);

// Check if the username already exists
$checkQuery = "SELECT * FROM users WHERE username = '$username'";
$checkResult = mysqli_query($conn, $checkQuery);

if (mysqli_num_rows($checkResult) > 0) {
    echo $USER_EXISTS;
} elseif (!preg_match('/^[a-zA-Z0-9]{3,}$/', $username)) {
    echo $USER_INVALID;
} else {
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert the user's information into the database
    $insertQuery = "INSERT INTO users (userID, user_type, username, password, telegram) VALUES ('$userID', 'member', '$username', '$hashed_password', '$telegram')";

    if (mysqli_query($conn, $insertQuery)) {
        echo json_encode(
            array(
                'status'=>"ok",
                'message'=>"Created account $username, redirecting to login"
            )
        );
    } else {
        error_log("Error: " . $insertQuery . "<br>" . mysqli_error($conn));
        echo $INTERNAL_ERROR;
    }
}

// Close the database connection
mysqli_close($conn);
?>

