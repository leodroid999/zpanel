<?php
function generate_index_file($vars){
$panelId="";
if(is_array($vars)){
  $panelId=$vars['panelId'];
}
else{
  return false;
}
$contents = <<<EOF
<!DOCTYPE html>
<html>
<head>
 <title>Logo with Variables</title>
 <style>
  .center {
   display: flex;
   justify-content: center;
   align-items: center;
   flex-direction: column;
   height: 100vh;
  }

    body {
    font-family: 'PT Sans', sans-serif;
  }
 </style>
</head>
<body>
 <div class="center">
     <link href="https://fonts.googleapis.com/css?family=PT+Sans" rel="stylesheet">
  <img src="assets/shark.jpeg" alt="Shark Logo" width="200px">
  $panelId
 </div>
</body>
</html>
EOF;
return $contents;
}
?>