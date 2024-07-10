<?php
    function generate_php_file($token,$pageIdRnd){
        $content = <<<EOD
            <?php
                require_once "scripts/setupPage.php";
                setupPage ("$token", "$pageIdRnd");
            ?>
        EOD;
        return $content;
    }
?>