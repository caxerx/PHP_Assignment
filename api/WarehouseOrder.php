<?php
require_once("Response.php");
session_start();
if (!isset($_SESSION['warehouse'])) {
    Response::Unauthorized();
}

require_once("Connection.php");
require_once("Routes.php");
new Routes($conn, new WarehouseOrder());


class WarehouseOrder
{
    function get($conn)
    {
        session_start();
        $stmt = $conn->prepare("SELECT Orders.OrderId, Suppliers.Name as SupplierName, Stock.Name, Orders.Amount, Orders.PurchaseDate, Orders.DeliveryDate, Orders.ReceivedDate FROM `Orders` LEFT JOIN SupplierStock ON Orders.SupplierStockId = SupplierStock.SupplierStockId LEFT JOIN Stock ON SupplierStock.StockId = Stock.StockId LEFT JOIN Suppliers ON SupplierStock.SupplierId=Suppliers.SupplierId WHERE Orders.DeliveryDate IS NOT NULL;");
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
        isset($data["ReceivedDate"]) or die(Response::RequireFieldEmpty());
        $stmt = $conn->prepare("UPDATE Orders SET ReceivedDate = ? WHERE OrderId = ?;");
        $stmt->bind_param("si", $data["ReceivedDate"], $data["OrderId"]);
        $stmt->execute();
        $stmt->close();
        Response::OK($data);
    }

}

?>