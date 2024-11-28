<?php

$sqlx = "SELECT TrafficStatus FROM logs WHERE SessionID = '$x'";
$resultz = mysqli_query($conn, $sqlx);

if (mysqli_num_rows($resultz) > 0) {
  // output data of each row
  while($row = mysqli_fetch_assoc($resultz)) {


if ($disable_forcedredirect !== "active")
{
  // nothing to do :)
}
else{

      if ($row["TrafficStatus"] == 'redirect') {echo '<script>window.location = "end.php";</script>';}


      }
  }
}

?>
