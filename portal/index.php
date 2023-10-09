<?php
session_start();

/* echo $_SESSION['userVerified']; echo "<br>";
echo $_SESSION['userSessionPass']; echo "<br>";
echo $_SESSION['userVerifiedExpiry']; echo "<br>";

 $_SESSION['userID']; echo "<br>"; */
//echo $_SESSION['username']; echo "<br>";

$userpassword = $_SESSION['userSessionPass'];
$userID = $_SESSION['userID'];

include 'db.php';

// Connect to the database
$conn = mysqli_connect($host, $user, $password, $dbname); 

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}



// Query to retrieve the hashed password
$sql = "SELECT password FROM users WHERE userID = '$userID' LIMIT 1";
$result = $conn->query($sql);

// Check if the query was successful
if ($result && $result->num_rows > 0) {
    // Fetch the first row
    $row = $result->fetch_assoc();
    // Echo the hashed password
    $hashed_password = $row['password'];



} else {
    echo "No results found.";
}


// Check if the user's password matches the hashed password
 if (password_verify($userpassword, $hashed_password)) {
    // echo "Welcome! Session Length & HASH integrity valid";
    // echo $hashed_password;
    // echo $userpassword;
 } else {
     //echo "Password does not match.";
     session_destroy();
     header("location: login.html");

 }



echo "Welcome ".$_SESSION['username'] ."#". $_SESSION['userID'];

 ?>

 <div id="right">Balance:</div>
 <br>My Panels

   <style>
   #right{
   float:right;
   }
   </style>
