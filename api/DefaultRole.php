<?php

require_once("Response.php");
require_once("Routes.php");
new Routes(null, new DefaultRole());


class DefaultRole
{
    function get()
    {
        if (isset($_COOKIE['default_role'])) {
            Response::OK(array('DefaultRole' => $_COOKIE['default_role']));
        } else {
            Response::OK(array('DefaultRole' => 'Supplier'));
        }
    }

    function post($c, $data)
    {
        if (isset($data['DefaultRole'])) {
            setcookie("default_role", $data['DefaultRole'], 2147483647);
            Response::OK(array('DefaultRole' => $_COOKIE['default_role']));
        } else {
            Response::RequireFieldEmpty();
        }
    }
}

?>