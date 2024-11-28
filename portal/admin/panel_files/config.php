<?php
function generate_config_php($vars){
    $node = $vars["nodeId"];
    $panel = $vars["panelId"];
    $content = <<<EOC
    <?php
    \$domain = \$_GET["d"];
    \$target = "$panel.$node";
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
    EOC;
    return $content;
}

?>