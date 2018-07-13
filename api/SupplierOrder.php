<?php

require_once("Response.php");
session_start();
if (!isset($_SESSION['supplier'])) {
    Response::Unauthorized();
}


require_once("Connection.php");
require_once("Routes.php");
new Routes($conn, new SupplierOrder());


class SupplierOrder
{
    function get($conn)
    {
        $stmt = $conn->prepare("SELECT Orders.OrderId, Stock.Name, Orders.Amount, Orders.PurchaseDate, Orders.DeliveryDate, Orders.ReceivedDate FROM `Orders` LEFT JOIN SupplierStock ON Orders.SupplierStockId = SupplierStock.SupplierStockId LEFT JOIN Stock ON SupplierStock.StockId = Stock.StockId LEFT JOIN Suppliers ON SupplierStock.SupplierId = Suppliers.SupplierId WHERE SupplierStock.SupplierId = ? AND Orders.Approved = 1;");
        $stmt->bind_param("i", $_SESSION['supplier']);
        $stmt->execute();
        if ($rs = $stmt->get_result()) {
            $row = $rs->fetch_all(MYSQLI_ASSOC);
            Response::OK($row);
        } else {
            Response::OK([]);
        }
    }

    function patch($conn, $data)
    {
        isset($data["OrderId"]) or die(Response::RequireFieldEmpty());
        isset($data["DeliveryDate"]) or die(Response::RequireFieldEmpty());
        $stmt = $conn->prepare("UPDATE Orders SET DeliveryDate = ? WHERE OrderId = ?;");
        $stmt->bind_param("si", $data["DeliveryDate"], $data["OrderId"]);
        $stmt->execute();
        $stmt->close();
        Response::OK($data);
    }

}

/*
 * SELECT Orders.OrderId, Stock.Name, Orders.Amount, Orders.PurchaseDate, Orders.DeliveryDate, Orders.ReceivedDate FROM `Orders` LEFT JOIN SupplierStock ON Orders.SupplierStockId = SupplierStock.SupplierStockId LEFT JOIN Stock ON SupplierStock.StockId = Stock.StockId;
 */

/*
 * UPDATE Orders SET DeliveryDate = ? WHERE OrderId = ?;
 */
?>