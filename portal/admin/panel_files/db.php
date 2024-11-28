<?php
    require_once "../lib/BlueprintCfg.php";
    function generate_db_file($node,$panelId,$userId){
        $sqlKey = $node['sql_key'];
        $sqlUser = $node['sql_user'];
        $nodeName = $node['NodeName'];
        $content=<<<EOF
        <?php
        \$servername = "localhost";
        \$username = "$sqlUser";
        \$password = "$sqlKey";
        \$db = "$panelId";
        \$nodeName = "$nodeName";
        \$panelId = "$panelId";
        \$userId = "$userId";
        ?>
        EOF;
        return $content;
    }
?>
