<?php
require_once("Response.php");
session_start();
if (!isset($_SESSION['supplier'])) {
    Response::Unauthorized();
}

require_once("Connection.php");
require_once("Routes.php");

new Routes($conn, new SupplierSupplierStock());


class SupplierSupplierStock
{
    function get($conn)
    {
        $stmt = $conn->prepare("SELECT SupplierStock.SupplierStockId, Stock.StockId, Stock.Name, SupplierStock.Amount FROM SupplierStock LEFT JOIN Stock ON SupplierStock.StockId = Stock.StockId WHERE SupplierStock.SupplierId = ?;");
        $stmt->bind_param("i", $_SESSION['supplier']);
        $stmt->execute();
        if ($rs = $stmt->get_result()) {
            $row = $rs->fetch_all(MYSQLI_ASSOC);
            Response::OK($row);
        } else {
            Response::OK([]);
        }
    }

    function post($conn, $data)
    {
        isset($data["StockId"]) or Response::RequireFieldEmpty();
        isset($data["Amount"]) or Response::RequireFieldEmpty();

        $stmt = $conn->prepare("SELECT * FROM `SupplierStock` WHERE `StockId` = ? AND `SupplierId` = ?;");
        $stmt->bind_param("ii", $data["StockId"], $_SESSION['supplier']);
        $stmt->execute();
        $rs = $stmt->get_result();
        if ($rs && count($rs->fetch_all(MYSQLI_ASSOC)) > 0) {
            Response::Fail("Already contains a Supplier Stock with Stock Id " . $data['StockId']);
        } else {
            $stmt->close();
            $stmt = $conn->prepare("INSERT INTO `SupplierStock` (`SupplierStockId`, `SupplierId`, `StockId`, `Amount`) VALUES (0, ?, ?, ?);");
            $stmt->bind_param("iii", $_SESSION['supplier'], $data["StockId"], $data["Amount"]);
            $stmt->execute();
            $stmt->close();
            Response::OK($data);
        }

    }

    function delete($conn, $data)
    {
        isset($data["SupplierStockId"]) or Response::RequireFieldEmpty();

        $stmt = $conn->prepare("DELETE FROM `SupplierStock` WHERE `SupplierStockId` = ?;");
        $stmt->bind_param("i", $data["SupplierStockId"]);
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