<?php

class Response
{
    public static function OK($data)
    {
        $result = array("success" => true, "data" => $data);
        echo json_encode($result);
    }


    public static function Fail($message)
    {
        $result = array("success" => false, "error" => $message);
        echo json_encode($result);
    }

    public static function Unauthorized()
    {
        header("HTTP/1.1 401 Unauthorized");
        die();
    }
}