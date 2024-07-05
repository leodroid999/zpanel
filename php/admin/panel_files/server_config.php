<?php
    function generate_server_config($vars){
        $nodeId="";
        $panelId="";
        if(is_array($vars)){
            $nodeId=$vars['nodeId'];
            $panelId=$vars['panelId'];
        }
        else{
            return false;
        }
        //
        $contents = <<<EOF
        <VirtualHost *:80>
            ServerName $panelId.$nodeId
            DocumentRoot /var/www/html/$panelId

            <Directory /var/www/html/$panelId>
                Options Indexes FollowSymLinks MultiViews
                AllowOverride All
                Require all granted
            </Directory>

            ErrorLog \${APACHE_LOG_DIR}/$panelId-error.log
            CustomLog \${APACHE_LOG_DIR}/$panelId-access.log combined
        </VirtualHost>
        EOF;
        return $contents;
    }
?>