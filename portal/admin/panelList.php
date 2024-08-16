<?php
    require_once "lib/DB.php";
    require_once "lib/ErrorHandler.php";
    require_once "lib/cors.php";
    include "lib/authcheck.php";
    if($user['user_type']!='admin'){
        return ErrorHandler::authError();
    }
    $panels = DB::getPanelList($conn);
    if($panels===null){
        error_log("Error loading panel list: " . mysqli_error());
        ErrorHandler::serverError();
    }
    if(count($panels)==0){
        echo json_encode(array(
            "status"=>"ok",
            "panels"=>[],
        ));
    }
    else{
        echo json_encode(array(
            "status"=>"ok",
            "panels"=>$panels,
        ));
    }
?>