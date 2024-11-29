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

$INVITE_INVALID=json_encode(
    array(
        'status'=>'error',
        'error'=>'INVITE_INVALID',
        'message'=>'There is not the invite code existing.'
    )
);

$INVITE_EXPIRED=json_encode(
    array(
        'status'=>'error',
        'error'=>'INVITE_EXPIRED',
        'message'=>'The invite code was expired.'
    )
);

$INVITE_OVER_USERS=json_encode(
    array(
        'status'=>'error',
        'error'=>'INVITE_OVER_USERS',
        'message'=>'The invite code was over used.'
    )
);


// Connect to the database
$conn = DB::connect();


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
$invite = mysqli_real_escape_string($conn, $_POST['referal']);


// Check if the invite code already exists
if($invite != ''){
    $checkQuery = "SELECT * FROM invites WHERE code = '$invite'";
    $checkResult = mysqli_query($conn, $checkQuery);
    if (mysqli_num_rows($checkResult) == 0) {
        echo $INVITE_INVALID;
        exit;
    }
    
    $row = $checkResult->fetch_array();

    if($row["expired"] <= time()){
        echo $INVITE_EXPIRED;
        exit;
    }
    
    if($row["users"] == $row["used"]){
        echo $INVITE_OVER_USERS;
        exit;
    }
}



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
        
        if($invite != ''){
            $used = $row["used"] + 1;
            $updateQuery = "UPDATE invites set used='$used' WHERE code='$invite'";
            mysqli_query($conn, $updateQuery);
        }

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

