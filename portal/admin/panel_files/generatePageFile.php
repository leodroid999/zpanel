<?php
    function generate_page_file($pageId){
        
        $content=<<<EOF
        <?php
        \$pageId = "$pageId";
        ?>
        EOF;
        return $content;
    }
?>
