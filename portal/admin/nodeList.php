<?php
    require_once "lib/DB.php";
    require_once "lib/ErrorHandler.php";
    require_once "lib/cors.php";
    include "lib/authcheck.php";
    if($user['user_type']!='admin'){
        return ErrorHandler::authError();
    }
    $nodes= DB::getNodeList($conn);
    if($nodes===null){
        error_log("Error loading panel list: " . mysqli_error());
        ErrorHandler::serverError();
    }
    if(count($nodes)==0){
        echo json_encode(array(
            "status"=>"ok",
            "nodes"=>[],
        ));
    }
    else{
        echo json_encode(array(
            "status"=>"ok",
            "nodes"=>$nodes,
        ));
    }
?>