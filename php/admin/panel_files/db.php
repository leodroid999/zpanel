<?php
    require_once "../lib/BlueprintCfg.php";
    function generate_db_file($rand,$node,$panelId,$pageId,$chatId){
        $pageIdRand = $pageId . $rand;
        $sqlKey = $node['sql_key'];
        $nodeDb = $node['NodeName'];
        $chatId = $chatId;

        $content=<<<EOF
        <?php
        \$servername = "localhost";
        \$username = "$nodeDb";
        \$password = "$sqlKey";
        \$panelID = "$panelId";
        \$nodeDB = "$nodeDb";
        \$logDB = "$panelId";
        \$pageID = "$pageIdRand";
        \$chatID = "$chatId";

        \$nodeConn = mysqli_connect(\$servername, \$username, \$password, \$nodeDB);
        \$logConn = mysqli_connect(\$servername, \$username, \$password, \$logDB);
        ?>
        EOF;
        return $content;
    }
?>
