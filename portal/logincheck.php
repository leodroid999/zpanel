<?php

include 'cors.php';
// Database connection parameters
include 'lib/DB.php';


use Library\DB as DB;

session_start();
// Connect to the database
$conn = DB::connect();

// Check connection
if (!$conn) {
    error_log("Connection failed: " . mysqli_connect_error());
}


if (isset($_POST["remember_token"])) {
    $userId = $_SESSION['userID'];

    $query = "SELECT remember_expires from users where userId='$userId'";
    if ($result = mysqli_query($conn, $query)) {

        $expires_at = $result->fetch_object()->remember_expires;
        $today = Date('Y-m-d H:i:s');

        if ($today < $expires_at)
            $SUCCESS = json_encode(
                array(
                    'status' => "ok",
                    'message' => "Login sucessfull , redirecting..",
                )
            );
        else
            $SUCCESS = json_encode(
                array(
                    'status' => "no",
                    'message' => "Remember token was expired",
                )
            );

        echo $SUCCESS;
        return;
    } else {
        error_log("Error: " . $query . "<br>" . mysqli_error($conn));
        echo $query;
        return;
    }
}


$_SESSION['userID'] = null;
$_SESSION['username'] = null;

// Rate Limiting
$rateLimit = 3; // Maximum number of login attempts
$rateLimitDuration = 10; // Time window in seconds


// Get the user's information from the form
$username = $_POST['username'];
$password = $_POST['password'];
$remember_me = $_POST['remember_me'];

$INTERNAL_ERROR = json_encode(
    array(
        'status' => "error",
        'error' => 'SERVER_ERROR',
        'message' => "There was a error, try again later"
    )
);

$RATE_LIMIT = json_encode(
    array(
        'status' => "error",
        'error' => 'RATE_LIMIT',
        'message' => "Rate limit exceeded. Please try again later."
    )
);



$INVALID_CREDS = json_encode(
    array(
        'status' => 'error',
        'error' => 'INVALID_CREDS',
        'message' => 'Invalid Credentials'
    )
);


$loginAttempts = 0;
$currentTime = time();
$lastAttemptTime = 0;

// Check if rate limit has been exceeded
if (isset($_SESSION['loginAttempts']) && isset($_SESSION['lastAttemptTime'])) {
    $loginAttempts = $_SESSION['loginAttempts'];
    $lastAttemptTime = $_SESSION['lastAttemptTime'];
}

$loginAttempts = $loginAttempts + 1;

// Calculate the time difference in seconds
$timeDifference = $currentTime - $lastAttemptTime;

// Reset login attempts if the time window has passed
if ($timeDifference > $rateLimitDuration) {
    $loginAttempts = 0;
}

//save login attempts
$_SESSION['loginAttempts'] = $loginAttempts;
$_SESSION['lastAttemptTime'] = $currentTime;

// Check if rate limit has been exceeded
if ($loginAttempts > $rateLimit) {
    echo $RATE_LIMIT;
    return;
}


$user = DB::getUserAuth($conn, $username);
if ($user) {
    $user = $user[0];
} else {
    echo $INVALID_CREDS;
    return;
}

if (password_verify($password, $user['password'])) {


    // Reset login attempts on successful login
    $loginAttempts = 0;

    // Create userVerified session for 36 hours
    $_SESSION['userVerified'] = true;
    $_SESSION['userSessionPass'] = $password;
    $_SESSION['userVerifiedExpiry'] = time() + (36 * 60 * 60);

    // Store the user ID and username in session variables
    $_SESSION['userID'] = $user["userId"];
    $_SESSION['username'] = $username;

    //reset attempts on valid login
    $_SESSION['loginAttempts'] = 0;
    $_SESSION['lastAttemptTime'] = 0;



    // =======================      Remembe token       =================== //

    $remember_token = '';
    $userId = $user["userId"];
    $expires_at = Date('Y-m-d H:i:s');

    if ($remember_me == 'true') {
        $remember_token = password_hash(time(), PASSWORD_DEFAULT);
        $expires_at = Date('Y-m-d H:i:s', strtotime('+3 days'));
    }

    $query = "UPDATE users SET remember_token='$remember_token', remember_expires='$expires_at' where userId='$userId'";

    if (mysqli_query($conn, $query)) {
        $SUCCESS = json_encode(
            array(
                'status' => "ok",
                'message' => "Login sucessfull , redirecting..",
                'remeber_token' => $remember_token
            )
        );

        echo $SUCCESS;
        return;
    } else {
        error_log("Error: " . $query . "<br>" . mysqli_error($conn));
        echo $query;
        return;
    }
} else {
    echo $INVALID_CREDS;
    return;
}

mysqli_close($conn);
