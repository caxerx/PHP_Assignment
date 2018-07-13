<?php
require_once("Connection.php");
require_once("Routes.php");
require_once("Response.php");
new Routes($conn, new SupplierOrder());


class SupplierOrder
{
    function get($conn)
    {
        session_start();
        $approved = 1;
        $stmt = $conn->prepare("SELECT Orders.OrderId, Stock.Name, Orders.Amount, Orders.PurchaseDate, Orders.DeliveryDate, Orders.ReceivedDate FROM `Orders` LEFT JOIN SupplierStock ON Orders.SupplierStockId = SupplierStock.SupplierStockId LEFT JOIN Stock ON SupplierStock.StockId = Stock.StockId LEFT JOIN Suppliers ON SupplierStock.SupplierId=Suppliers.SupplierId WHERE Suppliers.SupplierId = ? AND Orders.Approved = ?;");
        $stmt->bind_param("ii", $_SESSION['supplier'], $approved);
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