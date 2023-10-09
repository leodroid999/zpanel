<?php
  class Util {
    public static function sync_output(){
        ob_flush();
        flush();
    }
    
    public static function output_line($content)
    {
        echo ($content . "\n");
        Util::sync_output();
    }
    
    public static function print_lines($buffer)
    {
        $lines = explode("\n", $buffer);
        foreach ($lines as $line) {
            Util::output_line($line);
        }
    }    
  }
?>