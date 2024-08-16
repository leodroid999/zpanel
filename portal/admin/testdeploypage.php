<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    error_reporting(E_ALL);
    require_once "lib/DB.php";
    $conn = DB::connect();
    $panels = DB::getPanelList($conn);
    $pages = DB::getPageList($conn);
?>
<!DOCTYPE html>
<html>
<head>
  <title>Deploy Page</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

  <form id="myForm">
    <div style="text-align: left;">
      Deploy page<br/>
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
      <label for="pageId">Page:</label>
      <select id="pageId" name="pageId" placeholder="Select pageid">
      <?php 
            if($pages){
                foreach($pages as $page){
                    $pageId=$page['blueprint'];
                    echo("<option value='".$pageId."'>".$pageId.'</option>');
                }
            }
         ?>
      </select>
      <label for="folderName">Folder:</label>
      <input type="text" id="folderName" name="folderName" value=""></input>
      <button type="button" onclick="submitForm()">Deploy</button>
    </div>
  </form>

  <textarea id="i" style="height: 80vh; width: 60vw; margin-top: 1em;"></textarea>

  <script>
    function submitForm() {
      document.getElementById("i").innerHTML = "";
      var xhr = new XMLHttpRequest();
      var url = "deploypage.php";
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
      let valueSelected=document.getElementById("panelSelect").value;
      const vars = valueSelected.split('@');
      const nodeId = vars[1];
      const panelId = vars[0];
      formData.append("nodeId",nodeId);
      formData.append("panelId",panelId);
      console.log(formData);
      xhr.send(formData);
    }
  </script>

</body>
</html>
