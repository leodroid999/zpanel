<?php
    require_once "lib/DB.php";
    require_once "lib/ErrorHandler.php";
    require_once "lib/cors.php";
    include "lib/authcheck.php";
    if($user['user_type']!='admin'){
        return ErrorHandler::authError();
    }
    $users= DB::getUserList($conn);
    if($users===null){
        error_log("Error loading panel list: " . mysqli_error());
        ErrorHandler::serverError();
    }
    if(count($users)==0){
        echo json_encode(array(
            "status"=>"ok",
            "nodes"=>[],
        ));
    }
    else{
        echo json_encode(array(
            "status"=>"ok",
            "users"=>$users,
        ));
    }
?>