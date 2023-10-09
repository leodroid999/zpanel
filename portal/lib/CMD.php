<?php
    require_once "Util.php";
    class CMD{
        
        public static function connect_ssh($server,$user,$pass){
            $ssh_conn=ssh2_connect($server);
            if(!$ssh_conn){
                Util::output_line("Cannot connect to hostname/ip");
                return false;
            }
            $auth=ssh2_auth_password($ssh_conn,$user,$pass);
            if(!$auth){
                Util::output_line("Wrong username/password");
                return false;
            }
            return $ssh_conn;
        }
        
        public static function run_cmd($ssh,$cmd){
            Util::output_line("Running: $cmd");
            $stream_stdio = ssh2_exec($ssh,$cmd,"xterm");
            if(!$stream_stdio){
                Util::output_line("Failed to run command");
                return false;
            }
        
            Util::output_line("Waiting for output.. &#13;&#10;");
            usleep(900000);
        
            $stream_stderr=ssh2_fetch_stream($stream_stdio,SSH2_STREAM_STDERR);
            stream_set_blocking($stream_stderr,false); 
            stream_set_blocking($stream_stdio,false);
        
            $wait=true;
            $timeout=false;
            $waited=0;
            while(!feof($stream_stdio) or !feof($stream_stderr)){
                # sleep only after not reading any data;
                if($wait){
                    usleep(50000);
                    echo("");
                    Util::sync_output();
                }
                $out=stream_get_contents($stream_stdio);
                if($out!=''){
                    $waited=0;
                    $wait=false;
                    Util::print_lines($out);
                }
                else{
                    $wait=true;
                }
                $out=stream_get_contents($stream_stderr);
                if($out!=''){
                    $waited=0;
                    $wait=false;
                    Util::print_lines($out);
                }
                else{
                    $wait=true;
                }
            }
            Util::output_line("!!cmd end");
        }
    }
?>