<?php
    function generate_start_page($pageName){
        $content = <<<EOD
            <?php
                header('Location: $pageName');
            ?>
        EOD;
        return $content;
    }
?>