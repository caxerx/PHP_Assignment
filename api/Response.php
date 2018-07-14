<?php

class Response
{
    public static function OK($data)
    {
        $result = array("success" => true, "data" => $data);
        Response::DieResponse($result);
    }


    public static function Fail($message)
    {
        $result = array("success" => false, "error" => $message);
        Response::DieResponse($result);
    }

    public static function Unauthorized()
    {
        header("HTTP/1.1 401 Unauthorized");
        Response::DieResponse(array("success" => false, "error" => "Request Unauthorized"));
    }

    public static function RequireFieldEmpty()
    {
        Response::DieResponse(array("success" => false, "error" => "Required Field is Empty"));
    }

    private static function DieResponse($data)
    {
        die(json_encode($data));
    }
}

?>