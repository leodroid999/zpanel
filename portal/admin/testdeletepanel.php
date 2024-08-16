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
  <title>Delete Panel</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

  <form id="myForm">
    <div style="text-align: left;">
      Create panel<br/>
      <label for="panelSelect">Panel / Node ID:</label>
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
      <button type="button" onclick="submitForm()">Delete</button>
    </div>
  </form>

  <div id="i"></div>

  <script>
    function submitForm() {
      document.getElementById("i").innerHTML = "";
      var xhr = new XMLHttpRequest();
      var url = "deletepanel.php";
      var form = document.getElementById("myForm");
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
      let valueSelected=document.getElementById("panelSelect").value;
      const vars = valueSelected.split('@');
      const nodeId = vars[1];
      const panelId = vars[0];
      const data=new FormData();
      console.log(valueSelected);
      data.append("nodeId",nodeId);
      data.append("panelId",panelId);
      console.log(data);
      xhr.send(data);
    }
  </script>

</body>
</html>
