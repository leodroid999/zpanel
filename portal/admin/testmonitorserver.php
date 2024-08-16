<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    error_reporting(E_ALL);
    require_once "lib/DB.php";
    $conn = DB::connect();
    $hosts = DB::getHostList($conn);
?>
<!DOCTYPE html>
<html>
<head>
  <title>monitorserver</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

  <form id="myForm">
    <div style="text-align: left;">
      Test server state<br/>
      <label for="server-domain">Server Domain:</label>
      <select id="server-domain" name="server_domain" placeholder="Select domain">
      <?php 
            if($hosts){
                foreach($hosts as $host){
                    echo("<option value='".$host['domain']."'>".$host['domain'].'</option>');
                }
            }
         ?>
      </select>
      <br/>
      <button type="button" onclick="submitForm()">Submit</button>
    </div>
  </form>

  <div id="i"></div>

  <script>
    function submitForm() {
      document.getElementById("i").innerHTML = "";
      var xhr = new XMLHttpRequest();
      var url = "monitorserver.php";
      var form = document.getElementById("myForm");
      var formData = new FormData(form);

      xhr.open("POST", url, true);
      
      let last_index=0;
      xhr.onprogress = () => {
          let curr_index = xhr.responseText.length;
          if (last_index == curr_index) return; 
          let newData = xhr.responseText.substring(last_index, curr_index);
          let out=document.getElementById('i');
          out.innerHTML += newData;
          out.scrollTop = out.scrollHeight;
          last_index = curr_index;
      };

      xhr.send(formData);
    }
  </script>

</body>
</html>
