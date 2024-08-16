<?php
    require_once "lib/DB.php";
    require_once "lib/ErrorHandler.php";
    require_once "lib/cors.php";
    include "lib/authcheck.php";
    if($user['user_type']!='admin'){
        return ErrorHandler::authError();
    }
    $pages = DB::getPageList($conn);
    if($pages===null){
        error_log("Error loading page list: " . mysqli_error());
        ErrorHandler::serverError();
    }
    if(count($pages)==0){
        echo json_encode(array(
            "status"=>"ok",
            "pages"=>[],
        ));
    }
    else{
        echo json_encode(array(
            "status"=>"ok",
            "pages"=>$pages,
        ));
    }
?>