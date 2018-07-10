<?php

require_once("Connection.php");
require_once("Routes.php");
new Routes(null, new ReTe());


class ReTe
{
    function get($c, $d)
    {
        echo "get";
        print_r($d);
    }

    function put($c, $d)
    {
        echo "put";
        print_r($d);
    }

    function post($c, $d)
    {
        echo "post";
        print_r($d);
    }

    function delete($c, $d)
    {
        echo "delete";
        print_r($d);
    }
}

?>