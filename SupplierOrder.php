<?php
require_once("Connection.php");
require_once("Routes.php");
new Routes($conn, new SupplierOrder());


class SupplierOrder
{
    function get($conn)
    {
        session_start();
        $stmt = $conn->prepare("SELECT Orders.OrderId, Stock.Name, Orders.Amount, Orders.PurchaseDate, Orders.DeliveryDate, Orders.ReceivedDate FROM `Orders` LEFT JOIN SupplierStock ON Orders.SupplierStockId = SupplierStock.SupplierStockId LEFT JOIN Stock ON SupplierStock.StockId = Stock.StockId LEFT JOIN Suppliers ON SupplierStock.SupplierId=Suppliers.SupplierId WHERE Suppliers.SupplierId = ? AND Orders.Approved = ?;");
        $stmt->bind_param("ii", $_SESSION['supplier'], 1);
        if ($rs = $stmt->get_result()) {
            $row = $rs->fetch_all(MYSQLI_ASSOC);
            echo json_encode($row);
        }
    }

    function patch($conn, $data)
    {
        isset($data["OrderId"]) or die();
        isset($data["DeliveryDate"]) or die();
        $stmt = $conn->prepare("UPDATE Orders SET DeliveryDate = ? WHERE OrderId = ?;");
        $stmt->bind_param("si", $data["DeliveryDate"], $data["OrderId"]);
        $stmt->execute();
        $stmt->close();
    }

}

/*
 * SELECT Orders.OrderId, Stock.Name, Orders.Amount, Orders.PurchaseDate, Orders.DeliveryDate, Orders.ReceivedDate FROM `Orders` LEFT JOIN SupplierStock ON Orders.SupplierStockId = SupplierStock.SupplierStockId LEFT JOIN Stock ON SupplierStock.StockId = Stock.StockId;
 */

/*
 * UPDATE Orders SET DeliveryDate = ? WHERE OrderId = ?;
 */
?>