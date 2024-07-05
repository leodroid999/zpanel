
<?php

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

require_once '../db.php';


// Check if the z variable is set in the POST data
if (isset($_POST['z'])) {
  $session = $_POST['z'];
  
  $t = time();
echo $t;
echo $session;
  $sql = "UPDATE logs_editor SET Last_Online = '$t' WHERE SessionID = '$session';";
  $result = mysqli_query($nodeConn, $sql);
  mysqli_close($nodeConn);
} else {
  // Return an error message if the z variable is not set
  echo "Error: z variable not defined in POST data.";
}
?>
