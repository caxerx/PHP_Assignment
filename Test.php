<?php

require_once("Connection.php");
require_once("Routes.php");
new Routes($conn, new Test());


class Test
{
    function get($conn)
    {
        if ($rs = $conn->query("SELECT * FROM Orders")) {
            $row = $rs->fetch_all(MYSQLI_ASSOC);
            echo json_encode($row);
        }
    }
}

/*
 *
 *
 *
 * OrderId,	Stock	Name,	Order	Amount,	Orders	Purchase	Date,	Orders	Delivery	Date,
Order	Received	Date
 */

?>