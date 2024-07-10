
<?php
$allowedOrigins = [
   'https://z-panel.io',
   'http://z-panel.io',
   'http://z-panel.io:5173',
   'http://localhost:5173',
];

if (isset($_SERVER['HTTP_ORIGIN'])) {
   if (in_array($_SERVER['HTTP_ORIGIN'], $allowedOrigins)) {
      $http_origin = $_SERVER['HTTP_ORIGIN'];
   } else {
      $http_origin = "";
   }
   header("Access-Control-Allow-Origin: $http_origin");
   header("Access-Control-Allow-Credentials: true");
}
?>