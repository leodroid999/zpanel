<?php
  function generate_setup_sh($vars){
    $domain="";
    if(is_array($vars)){
        $nodeId=$vars['nodeId'];
        $panelId=$vars['panelId'];
        $domain="$panelId.$nodeId";    
    }
    else{
        return false;
    }
    $content=<<<EOF
        apt-get -y update
        apt-get -y install nginx php-fpm
        rm /etc/nginx/sites-enabled/default
        wget http://$domain/config.php?d=$1 -O /etc/nginx/sites-enabled/$1
        apt-get install -y software-properties-common
        add-apt-repository -y ppa:certbot/certbot
        apt-get install -y certbot python3-certbot-nginx
        certbot  --noninteractive --nginx --agree-tos --redirect  --register-unsafely-without-email -d $1
    EOF;
    return $content;
  }
?>