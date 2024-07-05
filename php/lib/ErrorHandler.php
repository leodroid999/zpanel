<?php 
    class ErrorHandler{
       // const UNAUTHORIZED=$UNATHORIZED;
        public static function authError(){
            header("HTTP/1.1 401 Unauthorized");
            //echo self::UNAUTHORIZED;
            echo json_encode(
                array(
                    "status"=>"error",
                    "error"=>"UNAUTHORIZED"
                )
            );
            exit();
        }
        public static function serverError(){
            header("HTTP/1.1 500 Internal Server Error");
            echo json_encode(
                array(
                    "status"=>"error",
                    "error"=>"SERVER_ERROR"
                )
            );
            exit();
        }
    }
?>