<?php
session_start();

  require_once './db.php';

  if (isset($_GET['secure'])){

      $boomerangUrl = $_GET['secure'];
      $_SESSION["boomerang"] = "$boomerangUrl";
  }

  if (isset($_SESSION["boomerang"])){

      $boomerangUrl = $_SESSION["boomerang"];
  }


  //echo"Token ID:$tokenID<br>";
//  echo"Page ID: $pageID<br>";

$chosenPage = $_GET["id"];
echo $chosenPage;
//Fetch Page info + Token info from //
$sql = "SELECT startpage
FROM blueprints
WHERE blueprint = '$chosenPage'";
$result = mysqli_query($nodeConn, "SET NAMES utf8");
$result = mysqli_query($nodeConn, $sql);
if (mysqli_num_rows($result) > 0) {
  // output data of each row
  while($row = mysqli_fetch_assoc($result))
  {

$filename = pathinfo($row["startpage"], PATHINFO_FILENAME);
$withouthtml = $filename;
header("location: debug.php/?page=$chosenPage&token=$withouthtml");

  }

}


if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}
?>
