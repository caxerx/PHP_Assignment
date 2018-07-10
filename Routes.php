<?php

class Routes
{
    function __construct($conn, $route_obj)
    {
        header('Content-Type: application/json; charset=utf-8');
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method === 'GET') {
            $request_data = $_GET;
        } else {
            $request_data = json_decode(file_get_contents('php://input'), TRUE);
        }
        $route_obj->{$method}($conn, $request_data);
    }

}

?>