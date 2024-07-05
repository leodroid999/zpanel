<?php


$session = $_POST['z'];
$blueprint=$_POST['blueprint'];
require_once '../db.php';



echo "🟢 $session<br> ";
echo "blueprint:$blueprint<br>";


//1. fetch Next_redirect 

$sql = "SELECT * FROM logs_editor WHERE SessionID = '$session';";
$result = mysqli_query($nodeConn, "SET NAMES utf8");
$result = mysqli_query($nodeConn, $sql);
if (mysqli_num_rows($result) > 0) {
  // output data of each row
  while($row = mysqli_fetch_assoc($result))
  {

      $next_redirect = $row['Next_Redirect'];
      echo $next_redirect;
  
  }
}

//2. Fetch to which page the Next_redirect is routing towards

$sql = "SELECT pagefile FROM `blueprints_tokens` WHERE tokenName = '$next_redirect' AND blueprint = '$blueprint'";
$result = mysqli_query($nodeConn, "SET NAMES utf8");
$result = mysqli_query($nodeConn, $sql);
if (mysqli_num_rows($result) > 0) {
  // output data of each row
  while($row = mysqli_fetch_assoc($result))
  {

      $pagefile = $row['pagefile'];
      echo $pagefile;
  
  }
}


//3. Redirect and make the session's Next_redirect back to NULL

if (!is_null($pagefile) || !empty($pagefile)) {
  echo "<script>window.location.replace('$pagefile');</script>";


  //clear next_redirect
  $sql = "UPDATE logs_editor SET Next_Redirect = '' WHERE SessionID = '$session';";
  $result = mysqli_query($nodeConn, $sql);

}





//$sql = "SELECT * FROM `blueprints_tokens` WHERE blueprint = 'youngones' AND tokenName = 'M1+m2+m3+M4'";


    
?>

