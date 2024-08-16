<?php
   ini_set('display_errors', 1);
   ini_set('display_startup_errors', 1);
   mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
   error_reporting(E_ALL);
   require_once "lib/DB.php";
   $conn = DB::connect();
   $panels = DB::getPanelList($conn);
?>
<!DOCTYPE html>
<html>
<head>
  <title>Host Panel</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

  <form id="myForm">
    <div style="text-align: left;">
      HostPanel();<br>
      <label for="panelId">PanelId</label>
      <select id="panelSelect" name="panelSelect" placeholder="Enter nodeId">
      <?php 
            if($panels){
                foreach($panels as $panel){
                    $fullname=$panel['panelId']."@".$panel["nodeId"];
                    echo("<option value='".$fullname."'>".$fullname.'</option>');
                }
            }
         ?>
      </select>
      <br>
      <label for="server-domain">Server Domain:</label>
      <input type="text" id="server-domain" name="server_domain" placeholder="Enter server domain">
      <br>
      <label for="server-username">Server Username:</label>
      <input type="text" id="server-username" name="server_username" placeholder="Enter server username">
      <br>
      <label for="password">Password:</label>
      <input type="password" id="password" name="password" placeholder="Enter password">
      <br>
      <button type="button" onclick="submitForm()">Submit</button>
    </div>
  </form>

  <textarea id="i" rows="25" cols="80"></textarea>

  <script>
    function submitForm() {
      document.getElementById("i").innerHTML = "";
      var xhr = new XMLHttpRequest();
      var url = "hostpanel.php";
      var form = document.getElementById("myForm");
      var formData = new FormData(form);

      xhr.open("POST", url, true);
      
      let last_index=0;
      xhr.onprogress = () => {
          let curr_index = xhr.responseText.length;
          if (last_index == curr_index) return; 
          let newData = xhr.responseText.substring(last_index, curr_index);
          let out=document.getElementById('i');
          out.innerHTML += newData.replace(/(<([^>]+)>)/gi, "");
          out.scrollTop = out.scrollHeight;
          last_index = curr_index;
      };
      let valueSelected=document.getElementById("panelSelect").value;
      const vars = valueSelected.split('@');
      const nodeId = vars[1];
      const panelId = vars[0];
      formData.append("nodeId",nodeId);
      formData.append("panelId",panelId);
      xhr.send(formData);
    }
  </script>

</body>
</html>
