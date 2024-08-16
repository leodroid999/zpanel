<?php
$session = $_POST['z'];
require_once '../../admin/config/config.php';
$t=time();


$sql = "UPDATE logs SET Last_Online = '$t' WHERE SessionID = '$session';";
$result = mysqli_query($conn, $sql);
mysqli_close($conn);
?>


