<?php
    include "cors.php";
    session_start();
    session_destroy();
    $SUCCESS=json_encode(
        array(
            'status'=>"ok",
            'message'=>"Logout sucessfull"
        )
    );
    echo $SUCCESS;
?>