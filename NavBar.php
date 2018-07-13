<?php

require_once("Routes.php");
require_once("Response.php");
new Routes(null, new NavBar());


class NavBar
{
    function get()
    {
        session_start();
        if (isset($_SESSION['supplier'])) {
            $nav = array();
            $nav[] = array("title" => "Approved Order", "icon" => "group", "href" => 'supplier-approved-order.html');
            $nav[] = array("title" => "Supplier Stock", "icon" => "group", "href" => 'supplier-stock.html');
            Response::OK($nav);
        } else {
            Response::Unauthorized();
        }
    }
}

?>