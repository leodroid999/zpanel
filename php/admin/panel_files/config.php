<?php
   function generate_config($vars){
    $nodeId="";
    $panelId="";
    if(is_array($vars)){
        $nodeId=$vars['nodeId'];
        $panelId=$vars['panelId'];
    }
    else{
        return false;
    }
   $content= <<<EOD
   <?php
   \$domain = \$_GET["d"];
   \$target = "$panelId.$nodeId";
   \$config = <<<EOF
    server {
        server_name \$domain;
        location / {
            proxy_set_header        X-Real-IP       \\\$remote_addr;
            proxy_set_header        X-Forwarded-For \\\$proxy_add_x_forwarded_for;
            proxy_set_header    Host    \$target;
            proxy_pass http://\$target/;
        }
   }
   EOF;
    echo(\$config);
   ?>
   EOD;
   return($content);
   }
?>
