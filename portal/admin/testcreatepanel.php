<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    error_reporting(E_ALL);
    require_once "lib/DB.php";
    $conn = DB::connect();
    $users = DB::getUserList($conn);
    $nodes = DB::getNodeList($conn);
?>
<!DOCTYPE html>
<html>
<head>
  <title>Create Panel</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

  <form id="myForm">
    <div style="text-align: left;">
      Create panel<br/>
      <label for="nodeId">Node ID:</label>
      <select id="nodeId" name="nodeId" placeholder="Enter nodeId">
      <?php 
            if($nodes){
                foreach($nodes as $node){
                    echo("<option value='".$node['nodeId']."'>".$node['nodeId'].'</option>');
                }
            }
         ?>
      </select>
      <br/>
      <label for="panelId">Panel Id</label>
      <input type="text" id="panelId" name="panelId" placeholder="Enter panelId">
      <br/>
      <label for="userId">User</label>
      <select name="userId" id="userId" placeholder="">
         <?php 
            if($users){
                foreach($users as $user){
                    echo("<option value='".$user['userId']."'>".$user['username'].'</option>');
                }
            }
         ?>
      </select>
      <button type="button" onclick="submitForm()">Create</button>
    </div>
  </form>

  <div id="i"></div>

  <script>
    function submitForm() {
      document.getElementById("i").innerHTML = "";
      var xhr = new XMLHttpRequest();
      var url = "createpanel.php";
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
