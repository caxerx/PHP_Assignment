<?php

require_once("Routes.php");
require_once("Response.php");
new Routes(null, new NavBar());


class NavBar
{
    function get()
    {
        session_start();
        $nav = array();
        if (isset($_SESSION['supplier'])) {
            $nav[] = array("title" => "Approved Order", "icon" => "receipt", "href" => 'approved-order.html');
            $nav[] = array("title" => "Supplier Stock", "icon" => "store", "href" => 'supplier-stock.html');
            Response::OK($nav);
        } else if (isset($_SESSION['warehouse'])) {
            $nav[] = array("title" => "Delivery Order", "icon" => "receipt", "href" => 'delivery-order.html');
            $nav[] = array("title" => "Warehouse Stock", "icon" => "store", "href" => 'warehouse-stock.html');
            Response::OK($nav);
        } else {
            Response::Unauthorized();
        }
    }
}

?>