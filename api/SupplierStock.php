<?php
require_once("Response.php");
session_start();
if (!isset($_SESSION['supplier'])) {
    Response::Unauthorized();
}

require_once("Connection.php");
require_once("Routes.php");
new Routes($conn, new SupplierStock());


class SupplierStock
{
    function get($conn)
    {
        $stmt = $conn->prepare("SELECT `StockId`,`Name` FROM Stock;");
        $stmt->execute();
        if ($rs = $stmt->get_result()) {
            $row = $rs->fetch_all(MYSQLI_ASSOC);
            Response::OK($row);
        } else {
            Response::OK([]);
        }
    }
}

/*
 * SELECT Orders.OrderId, Stock.Name, Orders.Amount, Orders.PurchaseDate, Orders.DeliveryDate, Orders.ReceivedDate FROM `Orders` LEFT JOIN SupplierStock ON Orders.SupplierStockId = SupplierStock.SupplierStockId LEFT JOIN Stock ON SupplierStock.StockId = Stock.StockId;
 */

/*
 * UPDATE Orders SET DeliveryDate = ? WHERE OrderId = ?;
 */
?>