<?php

$session = $_POST['z'];
$newdata = $_POST['newdata'];

$row = $_POST['row'];
require_once '../db.php';
$t=time();
$sql = "UPDATE logs_editor SET $row = '$newdata' WHERE SessionID = '$session';";
$result = mysqli_query($nodeConn, $sql);
mysqli_close($nodeConn);

echo "USERNAME: $session";

echo "SQL ROW: $row";

echo "VALUE:$newdata";






?>
